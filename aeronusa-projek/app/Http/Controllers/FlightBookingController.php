<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Models\Airport;
use App\Models\Flight;
use App\Models\Promo;
use App\Models\Booking;

class FlightBookingController extends Controller
{
    // 1. Tampilkan Landing Page + Filter Jadwal Pesawat
    public function index(Request $request)
    {
        $airports = Airport::orderBy('city')->get();
        $promos   = Promo::where('is_active', true)->get();

        $selectedFrom = $request->query('from', '');
        $selectedTo   = $request->query('to', '');
        $selectedDate = $request->query('date', now()->addDay()->format('Y-m-d'));
        $passengers   = max(1, (int) $request->query('pax', 1));

        $flightsQuery = Flight::query();
        if (!empty($selectedFrom)) {
            $flightsQuery->where('from_code', $selectedFrom);
        }
        if (!empty($selectedTo)) {
            $flightsQuery->where('to_code', $selectedTo);
        }
        $filteredFlights = $flightsQuery->get();

        // Daftar harga bagasi tambahan
        $baggageOptions = [
            ['weight' => 0,  'label' => 'Bagasi Gratis Bawaan Maskapai', 'price' => 0],
            ['weight' => 5,  'label' => '+5 Kg Bagasi Tercatat', 'price' => 95000],
            ['weight' => 10, 'label' => '+10 Kg Bagasi Tercatat', 'price' => 175000],
            ['weight' => 20, 'label' => '+20 Kg Bagasi Tercatat (Hemat 20%)', 'price' => 310000],
        ];

        // Jika pengguna juga mengecek Kode Booking (PNR)
        $checkedBooking = null;
        if ($request->filled('pnr')) {
            $checkedBooking = Booking::with('flight')
                ->where('pnr_code', strtoupper($request->query('pnr')))
                ->first();
        }

        return view('landing', compact(
            'airports',
            'promos',
            'filteredFlights',
            'baggageOptions',
            'selectedFrom',
            'selectedTo',
            'selectedDate',
            'passengers',
            'checkedBooking'
        ));
    }

    // 2. Simpan Pemesanan Tiket & Kunci Kursi di Database
    public function store(Request $request)
    {
        $validated = $request->validate([
            'flight_id'        => 'required|exists:flights,id',
            'passenger_name'   => 'required|string|max:100',
            'passenger_email'  => 'required|email',
            'flight_date'      => 'required|date',
            'pax_count'        => 'required|integer|min:1|max:6',
            'selected_seats'   => 'required|string', // Dikirim dari input hidden misal "2C,2D"
            'baggage_price'    => 'required|numeric|min:0',
            'extra_baggage_kg' => 'required|integer|min:0',
            'promo_code'       => 'nullable|string',
        ]);

        $seatsArray = array_filter(explode(',', $validated['selected_seats']));

        if (count($seatsArray) !== (int) $validated['pax_count']) {
            return back()->withErrors(['msg' => 'Jumlah kursi yang dipilih harus sesuai dengan jumlah penumpang.']);
        }

        return DB::transaction(function () use ($validated, $seatsArray) {
            $flight = Flight::lockForUpdate()->findOrFail($validated['flight_id']);
            $occupied = $flight->occupied_seats ?? [];

            // Cek apakah kursi sudah diambil orang lain
            foreach ($seatsArray as $seat) {
                if (in_array($seat, $occupied)) {
                    abort(422, "Kursi {$seat} baru saja terisi. Silakan pilih kursi lain.");
                }
            }

            // Hitung ulang harga di sisi server (keamanan agar tidak dimanipulasi dari inspect element)
            $baseTotal = $flight->base_price * $validated['pax_count'];
            $subtotal  = $baseTotal + $validated['baggage_price'];
            $discount  = 0;

            if (!empty($validated['promo_code'])) {
                $promo = Promo::where('code', strtoupper($validated['promo_code']))
                              ->where('is_active', true)
                              ->first();
                if ($promo && $subtotal >= $promo->min_tx) {
                    $discount = $promo->discount;
                }
            }

            $grandTotal = max(0, $subtotal - $discount);

            // Buat kode PNR unik 6 karakter (Contoh: AN892K)
            do {
                $pnr = 'AN' . strtoupper(Str::random(4));
            } while (Booking::where('pnr_code', $pnr)->exists());

            // Simpan ke tabel bookings
            $booking = Booking::create([
                'pnr_code'         => $pnr,
                'flight_id'        => $flight->id,
                'passenger_name'   => $validated['passenger_name'],
                'passenger_email'  => $validated['passenger_email'],
                'flight_date'      => $validated['flight_date'],
                'pax_count'        => $validated['pax_count'],
                'selected_seats'   => $seatsArray,
                'extra_baggage_kg' => $validated['extra_baggage_kg'],
                'baggage_price'    => $validated['baggage_price'],
                'promo_code'       => $validated['promo_code'],
                'discount_amount'  => $discount,
                'grand_total'      => $grandTotal,
                'status'           => 'confirmed',
            ]);

            // Update kursi terisi di tabel flights
            $flight->update([
                'occupied_seats' => array_values(array_unique(array_merge($occupied, $seatsArray)))
            ]);

            return redirect()->route('landing', ['pnr' => $booking->pnr_code])
                ->with('success', "Tiket berhasil dipesan! Kode Booking (PNR) Anda: {$booking->pnr_code}");
        });
    }

    // 3. Ajukan Refund / Pembatalan Tiket & Lepaskan Kursi Kembali
    public function refund(Request $request, $pnr)
    {
        $booking = Booking::with('flight')->where('pnr_code', strtoupper($pnr))->firstOrFail();

        if ($booking->status !== 'confirmed') {
            return back()->with('success', 'Tiket ini sudah dalam status pengajuan refund atau dibatalkan.');
        }

        DB::transaction(function () use ($booking, $request) {
            // Kembalikan kursi agar bisa dipilih penumpang lain lagi
            $flight = $booking->flight;
            $occupied = $flight->occupied_seats ?? [];
            $updatedSeats = array_values(array_diff($occupied, $booking->selected_seats ?? []));
            $flight->update(['occupied_seats' => $updatedSeats]);

            $booking->update([
                'status'        => 'refund_requested',
                'refund_reason' => $request->input('reason', 'Permintaan pengguna melalui Self-Service Portal'),
            ]);
        });

        return redirect()->route('landing', ['pnr' => $booking->pnr_code])
            ->with('success', "Pengajuan Refund untuk PNR {$booking->pnr_code} berhasil diproses dan kursi telah dikosongkan kembali.");
    }
}