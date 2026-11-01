@php
    // DATA CADANGAN OTOMATIS (10 Bandara & 12 Rute Penerbangan)
    if (!isset($airports) || count($airports) === 0) {
        $airports = collect([
            (object)['code' => 'CGK', 'city' => 'Jakarta', 'name' => 'Soekarno-Hatta Intl', 'zone' => 'WIB'],
            (object)['code' => 'DPS', 'city' => 'Denpasar (Bali)', 'name' => 'I Gusti Ngurah Rai Intl', 'zone' => 'WITA'],
            (object)['code' => 'SUB', 'city' => 'Surabaya', 'name' => 'Juanda Intl', 'zone' => 'WIB'],
            (object)['code' => 'YIA', 'city' => 'Yogyakarta', 'name' => 'Yogyakarta Intl', 'zone' => 'WIB'],
            (object)['code' => 'KNO', 'city' => 'Medan', 'name' => 'Kualanamu Intl', 'zone' => 'WIB'],
            (object)['code' => 'UPG', 'city' => 'Makassar', 'name' => 'Sultan Hasanuddin Intl', 'zone' => 'WITA'],
            (object)['code' => 'BPN', 'city' => 'Balikpapan', 'name' => 'Sultan Aji Muhammad Sulaiman', 'zone' => 'WITA'],
            (object)['code' => 'SRG', 'city' => 'Semarang', 'name' => 'Jenderal Ahmad Yani', 'zone' => 'WIB'],
            (object)['code' => 'PLM', 'city' => 'Palembang', 'name' => 'Sultan Mahmud Badaruddin II', 'zone' => 'WIB'],
            (object)['code' => 'LOP', 'city' => 'Lombok', 'name' => 'Zainuddin Abdul Madjid', 'zone' => 'WITA'],
        ]);
    }

    $selectedFrom = $selectedFrom ?? request('from', '');
    $selectedTo   = $selectedTo ?? request('to', '');
    $selectedDate = $selectedDate ?? request('date', date('Y-m-d', strtotime('+1 day')));
    $passengers   = isset($passengers) ? max(1, (int)$passengers) : max(1, (int)request('pax', 1));

    if (!isset($filteredFlights)) {
        $allDefaultFlights = collect([
            (object)[
                'id' => 1, 'flight_no' => 'GN-402', 'airline' => 'Garuda Nusantara', 'aircraft' => 'Boeing 737-800 NG',
                'from_code' => 'CGK', 'to_code' => 'DPS', 'dep_time' => '07:30', 'arr_time' => '10:25',
                'duration' => '1j 55m', 'type' => 'Langsung', 'class' => 'Ekonomi', 'base_price' => 1150000,
                'free_baggage' => 20, 'badge' => 'Paling Tepat Waktu (96%)', 'occupied_seats' => ['1B', '1C', '2A', '3D']
            ],
            (object)[
                'id' => 2, 'flight_no' => 'AN-718', 'airline' => 'AeroNusa Citilink', 'aircraft' => 'Airbus A320neo',
                'from_code' => 'DPS', 'to_code' => 'CGK', 'dep_time' => '14:15', 'arr_time' => '15:10',
                'duration' => '1j 55m', 'type' => 'Langsung', 'class' => 'Ekonomi', 'base_price' => 920000,
                'free_baggage' => 15, 'badge' => 'Rute Balik Populer', 'occupied_seats' => ['1A', '2B', '3A', '5D']
            ],
            (object)[
                'id' => 3, 'flight_no' => 'PS-209', 'airline' => 'Pelita Sky', 'aircraft' => 'Airbus A320-200',
                'from_code' => 'CGK', 'to_code' => 'SUB', 'dep_time' => '13:00', 'arr_time' => '14:30',
                'duration' => '1j 30m', 'type' => 'Langsung', 'class' => 'Ekonomi', 'base_price' => 760000,
                'free_baggage' => 20, 'badge' => 'Rekomendasi Populer', 'occupied_seats' => ['1D', '2E', '4C']
            ],
            (object)[
                'id' => 4, 'flight_no' => 'PS-210', 'airline' => 'Pelita Sky', 'aircraft' => 'Airbus A320-200',
                'from_code' => 'SUB', 'to_code' => 'CGK', 'dep_time' => '16:00', 'arr_time' => '17:30',
                'duration' => '1j 30m', 'type' => 'Langsung', 'class' => 'Ekonomi', 'base_price' => 750000,
                'free_baggage' => 20, 'badge' => 'Jadwal Sore Favorit', 'occupied_seats' => ['2C', '3B', '5A']
            ],
            (object)[
                'id' => 5, 'flight_no' => 'GN-510', 'airline' => 'Garuda Nusantara', 'aircraft' => 'Boeing 737-800 NG',
                'from_code' => 'CGK', 'to_code' => 'YIA', 'dep_time' => '08:45', 'arr_time' => '10:00',
                'duration' => '1j 15m', 'type' => 'Langsung', 'class' => 'Ekonomi', 'base_price' => 720000,
                'free_baggage' => 20, 'badge' => 'In-Flight Meal', 'occupied_seats' => ['1A', '3C']
            ],
            (object)[
                'id' => 6, 'flight_no' => 'BA-612', 'airline' => 'Batik Aero', 'aircraft' => 'Boeing 737-900ER',
                'from_code' => 'CGK', 'to_code' => 'KNO', 'dep_time' => '09:15', 'arr_time' => '11:35',
                'duration' => '2j 20m', 'type' => 'Langsung', 'class' => 'Ekonomi', 'base_price' => 1320000,
                'free_baggage' => 20, 'badge' => 'In-Flight Entertainment', 'occupied_seats' => ['2A', '2B', '4D']
            ],
            (object)[
                'id' => 7, 'flight_no' => 'GN-620', 'airline' => 'Garuda Nusantara', 'aircraft' => 'Airbus A330-300',
                'from_code' => 'CGK', 'to_code' => 'UPG', 'dep_time' => '06:30', 'arr_time' => '09:55',
                'duration' => '2j 25m', 'type' => 'Langsung', 'class' => 'Bisnis', 'base_price' => 1850000,
                'free_baggage' => 30, 'badge' => 'Kabin Wide-Body', 'occupied_seats' => ['1A', '1F', '2D']
            ],
            (object)[
                'id' => 8, 'flight_no' => 'AN-405', 'airline' => 'AeroNusa Citilink', 'aircraft' => 'Airbus A320neo',
                'from_code' => 'CGK', 'to_code' => 'BPN', 'dep_time' => '11:00', 'arr_time' => '14:15',
                'duration' => '2j 15m', 'type' => 'Langsung', 'class' => 'Ekonomi', 'base_price' => 1240000,
                'free_baggage' => 15, 'badge' => 'Akses IKN Nusantara', 'occupied_seats' => ['3A', '3B', '6C']
            ],
            (object)[
                'id' => 9, 'flight_no' => 'BA-881', 'airline' => 'Batik Aero', 'aircraft' => 'Airbus A320-200',
                'from_code' => 'SUB', 'to_code' => 'DPS', 'dep_time' => '16:20', 'arr_time' => '18:15',
                'duration' => '0j 55m', 'type' => 'Langsung', 'class' => 'Ekonomi', 'base_price' => 590000,
                'free_baggage' => 20, 'badge' => 'Rute Cepat 55 Menit', 'occupied_seats' => ['1C', '4E']
            ],
            (object)[
                'id' => 10, 'flight_no' => 'AN-312', 'airline' => 'AeroNusa Citilink', 'aircraft' => 'Airbus A320-200',
                'from_code' => 'YIA', 'to_code' => 'DPS', 'dep_time' => '12:10', 'arr_time' => '14:30',
                'duration' => '1j 20m', 'type' => 'Langsung', 'class' => 'Ekonomi', 'base_price' => 690000,
                'free_baggage' => 15, 'badge' => 'Rute Wisata Budaya', 'occupied_seats' => ['2A', '5B', '5C']
            ],
            (object)[
                'id' => 11, 'flight_no' => 'PS-114', 'airline' => 'Pelita Sky', 'aircraft' => 'Airbus A320-200',
                'from_code' => 'CGK', 'to_code' => 'PLM', 'dep_time' => '07:00', 'arr_time' => '08:05',
                'duration' => '1j 05m', 'type' => 'Langsung', 'class' => 'Ekonomi', 'base_price' => 610000,
                'free_baggage' => 20, 'badge' => 'Penerbangan Pagi', 'occupied_seats' => ['1B', '3E']
            ],
            (object)[
                'id' => 12, 'flight_no' => 'GN-708', 'airline' => 'Garuda Nusantara', 'aircraft' => 'Boeing 737-800 NG',
                'from_code' => 'DPS', 'to_code' => 'LOP', 'dep_time' => '15:00', 'arr_time' => '15:45',
                'duration' => '0j 45m', 'type' => 'Langsung', 'class' => 'Ekonomi', 'base_price' => 480000,
                'free_baggage' => 20, 'badge' => 'Island Hopping', 'occupied_seats' => ['2D', '4A']
            ],
        ]);

        $filteredFlights = $allDefaultFlights->filter(function ($f) use ($selectedFrom, $selectedTo) {
            $matchFrom = empty($selectedFrom) || $f->from_code === $selectedFrom;
            $matchTo   = empty($selectedTo) || $f->to_code === $selectedTo;
            return $matchFrom && $matchTo;
        });
    }

    if (!isset($promos) || count($promos) === 0) {
        $promos = collect([
            (object)['code' => 'AEROMEMBER', 'title' => 'Diskon Member Eksklusif', 'discount' => 150000, 'min_tx' => 800000, 'desc' => 'Potongan Rp 150.000 khusus member'],
            (object)['code' => 'TERBANGHEMAT', 'title' => 'Promo Terbang Pagi', 'discount' => 75000, 'min_tx' => 500000, 'desc' => 'Potongan Rp 75.000 semua rute'],
            (object)['code' => 'BALIESCAPE', 'title' => 'Spesial Liburan Bali', 'discount' => 200000, 'min_tx' => 1200000, 'desc' => 'Potongan Rp 200.000 min. transaksi 1,2 Juta'],
        ]);
    }

    if (!isset($baggageOptions)) {
        $baggageOptions = [
            ['weight' => 0,  'label' => 'Bagasi Gratis Bawaan Maskapai', 'price' => 0],
            ['weight' => 5,  'label' => '+5 Kg Bagasi Tercatat', 'price' => 95000],
            ['weight' => 10, 'label' => '+10 Kg Bagasi Tercatat', 'price' => 175000],
            ['weight' => 20, 'label' => '+20 Kg Bagasi Tercatat (Hemat 20%)', 'price' => 310000],
        ];
    }

    $checkedBooking = $checkedBooking ?? null;

    $quickRoutes = [
        ['from' => 'CGK', 'to' => 'DPS', 'label' => 'CGK → DPS (Bali)'],
        ['from' => 'DPS', 'to' => 'CGK', 'label' => 'DPS → CGK (Jakarta)'],
        ['from' => 'CGK', 'to' => 'SUB', 'label' => 'CGK → SUB (Surabaya)'],
        ['from' => 'SUB', 'to' => 'CGK', 'label' => 'SUB → CGK'],
        ['from' => 'CGK', 'to' => 'YIA', 'label' => 'CGK → YIA (Yogya)'],
        ['from' => 'CGK', 'to' => 'KNO', 'label' => 'CGK → KNO (Medan)'],
        ['from' => 'CGK', 'to' => 'UPG', 'label' => 'CGK → UPG (Makassar)'],
        ['from' => 'CGK', 'to' => 'BPN', 'label' => 'CGK → BPN (Balikpapan)'],
        ['from' => 'SUB', 'to' => 'DPS', 'label' => 'SUB → DPS'],
        ['from' => 'YIA', 'to' => 'DPS', 'label' => 'YIA → DPS'],
        ['from' => 'CGK', 'to' => 'PLM', 'label' => 'CGK → PLM (Palembang)'],
        ['from' => 'DPS', 'to' => 'LOP', 'label' => 'DPS → LOP (Lombok)'],
    ];
@endphp
<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AeroNusa — Pemesanan Tiket Pesawat, Bagasi & Kursi</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=JetBrains+Mono:wght@500;700&family=Plus+Jakarta+Sans:wght@700;800&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        aero: {
                            navy: '#0F172A',
                            sky: '#0284C7',
                            amber: '#F59E0B',
                            surface: '#F8FAFC'
                        }
                    },
                    fontFamily: {
                        heading: ['"Plus Jakarta Sans"', 'sans-serif'],
                        sans: ['Inter', 'sans-serif'],
                        mono: ['"JetBrains Mono"', 'monospace'],
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-aero-surface text-slate-800 font-sans antialiased">

    <!-- NAVBAR -->
    <header class="bg-aero-navy text-white sticky top-0 z-40 border-b border-slate-800 shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-aero-sky flex items-center justify-center font-heading font-extrabold text-xl">
                    ✈
                </div>
                <div>
                    <span class="font-heading font-extrabold text-xl tracking-tight">Aero<span class="text-aero-amber">Nusa</span></span>
                    <span class="hidden sm:inline-block ml-2 text-[11px] font-mono bg-slate-800 text-sky-400 px-2 py-0.5 rounded border border-slate-700">12 Active Routes</span>
                </div>
            </div>

            <nav class="hidden md:flex items-center gap-6 text-sm font-medium text-slate-300">
                <a href="#cari-tiket" class="hover:text-white transition">Cari Tiket</a>
                <a href="#jadwal-pesawat" class="hover:text-white transition">Rekomendasi Pesawat</a>
                <a href="#promo-member" class="hover:text-white transition">Promo Member</a>
                <a href="#cek-pesanan" class="hover:text-white transition">Cek / Refund Tiket</a>
            </nav>

            <div class="flex items-center gap-3">
                <a href="#cek-pesanan" class="text-xs sm:text-sm font-medium px-3 py-2 rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-200 border border-slate-700 transition">
                    Cek Kode PNR
                </a>
            </div>
        </div>
    </header>

    <!-- NOTIFIKASI -->
    @if(session('success'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
            <div class="bg-emerald-50 border-2 border-emerald-400 text-emerald-900 px-4 py-3 rounded-xl font-semibold text-sm flex justify-between items-center shadow-sm">
                <span>✓ {{ session('success') }}</span>
                <a href="#cek-pesanan" class="underline text-xs font-mono font-bold">Lihat Detail E-Ticket ↓</a>
            </div>
        </div>
    @endif

    @if($errors->any())
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
            <div class="bg-rose-50 border-2 border-rose-400 text-rose-900 px-4 py-3 rounded-xl font-semibold text-sm">
                ⚠️ {{ $errors->first() }}
            </div>
        </div>
    @endif

    <!-- HERO SECTION + PENCARIAN -->
    <section id="cari-tiket" class="relative bg-gradient-to-b from-aero-navy via-slate-900 to-slate-800 text-white pt-10 pb-24">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-3xl mb-8">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-sky-500/10 border border-sky-400/30 text-sky-300 text-xs font-medium mb-4">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    12 Rute Domestik Aktif • Pilih Kursi & Bagasi Real-Time • Refund Mudah
                </div>
                <h1 class="font-heading text-3xl sm:text-5xl font-extrabold tracking-tight leading-tight">
                    Pesan Tiket Pesawat Tanpa Biaya Tersembunyi.
                </h1>
                <p class="mt-3 text-slate-300 text-base sm:text-lg">
                    Bandingkan maskapai terbaik, atur kuota bagasi, pilih nomor kursi favorit di kabin, dan klaim promo member dalam satu layar.
                </p>
            </div>

            <div class="bg-white text-slate-800 rounded-2xl shadow-2xl p-5 sm:p-6 border border-slate-200">
                <form id="searchFlightForm" method="GET" action="{{ url('/') }}" class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
                    
                    <div class="md:col-span-3">
                        <label for="fromSelect" class="block text-xs font-semibold uppercase tracking-wider text-slate-500 mb-1.5">Bandara Asal</label>
                        <select id="fromSelect" name="from" class="w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-sm font-medium bg-white focus:outline-none focus:ring-2 focus:ring-aero-sky">
                            <option value="">Semua Bandara Asal</option>
                            @foreach($airports as $ap)
                                <option value="{{ $ap->code }}" {{ $selectedFrom === $ap->code ? 'selected' : '' }}>
                                    [{{ $ap->code }}] {{ $ap->city }} — {{ $ap->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="md:col-span-1 flex justify-center">
                        <button type="button" onclick="swapAirports()" title="Tukar Bandara Asal & Tujuan"
                            class="w-full md:w-10 h-10 rounded-xl bg-slate-100 hover:bg-sky-100 text-slate-700 hover:text-aero-sky border border-slate-300 font-bold text-base flex items-center justify-center transition">
                            ⇄
                        </button>
                    </div>

                    <div class="md:col-span-3">
                        <label for="toSelect" class="block text-xs font-semibold uppercase tracking-wider text-slate-500 mb-1.5">Bandara Tujuan</label>
                        <select id="toSelect" name="to" class="w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-sm font-medium bg-white focus:outline-none focus:ring-2 focus:ring-aero-sky">
                            <option value="">Semua Bandara Tujuan</option>
                            @foreach($airports as $ap)
                                <option value="{{ $ap->code }}" {{ $selectedTo === $ap->code ? 'selected' : '' }}>
                                    [{{ $ap->code }}] {{ $ap->city }} — {{ $ap->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-500 mb-1.5">Tanggal Pergi</label>
                        <input type="date" name="date" value="{{ $selectedDate }}"
                            class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-aero-sky">
                    </div>

                    <div class="md:col-span-1">
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-500 mb-1.5">Penumpang</label>
                        <input type="number" name="pax" min="1" max="6" value="{{ $passengers }}"
                            class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm font-mono font-bold text-center focus:outline-none focus:ring-2 focus:ring-aero-sky">
                    </div>

                    <div class="md:col-span-2 flex gap-2">
                        <button type="submit" class="w-full bg-aero-sky hover:bg-sky-700 text-white font-heading font-bold py-2.5 px-4 rounded-xl shadow-md transition">
                            Cari Tiket
                        </button>
                        @if($selectedFrom || $selectedTo)
                            <a href="{{ url('/') }}" class="px-3 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs font-semibold flex items-center">
                                Reset
                            </a>
                        @endif
                    </div>
                </form>

                <div class="mt-4 pt-3 border-t border-slate-100">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-xs font-bold text-slate-700">12 Rute Tersedia Cepat (Klik untuk Filter Instan):</span>
                        <a href="{{ url('/') }}" class="text-xs font-semibold text-aero-sky hover:underline">Tampilkan Semua (12 Jadwal)</a>
                    </div>
                    <div class="flex flex-wrap items-center gap-1.5 text-xs">
                        @foreach($quickRoutes as $qr)
                            @php
                                $isActiveRoute = ($selectedFrom === $qr['from'] && $selectedTo === $qr['to']);
                            @endphp
                            <a href="{{ url('/?from=' . $qr['from'] . '&to=' . $qr['to'] . '&date=' . $selectedDate . '&pax=' . $passengers) }}"
                               class="px-2.5 py-1 rounded-lg font-mono transition border {{ $isActiveRoute ? 'bg-aero-sky text-white border-aero-sky font-bold shadow-sm' : 'bg-slate-50 hover:bg-sky-50 text-slate-700 hover:text-aero-sky border-slate-200' }}">
                                {{ $qr['label'] }}
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- MAIN CONTENT -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-12 pb-20">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">

            <!-- DAFTAR JADWAL PESAWAT -->
            <div id="jadwal-pesawat" class="lg:col-span-7 space-y-4">
                <div class="bg-white rounded-2xl p-4 sm:p-5 border border-slate-200 shadow-sm flex flex-wrap items-center justify-between gap-2">
                    <div>
                        <h2 class="font-heading font-bold text-lg text-slate-900">Rekomendasi Penerbangan Aktif</h2>
                        <p class="text-xs text-slate-500">
                            Menampilkan {{ count($filteredFlights) }} jadwal tersedia untuk
                            <span class="font-mono font-semibold text-slate-700">{{ $selectedDate }}</span> ({{ $passengers }} Penumpang)
                        </p>
                    </div>
                    <span class="text-xs font-mono bg-emerald-50 text-emerald-700 border border-emerald-200 px-2.5 py-1 rounded-full">
                        Termasuk Pajak Bandara
                    </span>
                </div>

                @forelse($filteredFlights as $flight)
                    <div class="flight-card bg-white rounded-2xl p-5 border-2 border-slate-200 hover:border-aero-sky transition shadow-sm"
                         data-id="{{ $flight->id }}"
                         data-airline="{{ $flight->airline }}"
                         data-flightno="{{ $flight->flight_no }}"
                         data-from="{{ $flight->from_code }}"
                         data-to="{{ $flight->to_code }}"
                         data-dep="{{ $flight->dep_time }}"
                         data-arr="{{ $flight->arr_time }}"
                         data-price="{{ $flight->base_price }}"
                         data-freebag="{{ $flight->free_baggage }}"
                         data-occupied="{{ json_encode(is_array($flight->occupied_seats ?? null) ? $flight->occupied_seats : (json_decode($flight->occupied_seats ?? '[]', true) ?? [])) }}">

                        <div class="flex flex-wrap items-center justify-between gap-2 pb-3 mb-4 border-b border-slate-100">
                            <div class="flex items-center gap-2.5">
                                <span class="px-2.5 py-1 rounded-md bg-slate-900 text-white font-mono text-xs font-bold">
                                    {{ $flight->flight_no }}
                                </span>
                                <div>
                                    <h3 class="font-heading font-bold text-slate-900 text-base">{{ $flight->airline }}</h3>
                                    <p class="text-xs text-slate-500">{{ $flight->aircraft }} • Kelas {{ $flight->class }}</p>
                                </div>
                            </div>
                            @if(!empty($flight->badge))
                                <span class="text-xs font-semibold px-2.5 py-1 rounded-full bg-sky-50 text-aero-sky border border-sky-200">
                                    ★ {{ $flight->badge }}
                                </span>
                            @endif
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-12 gap-4 items-center">
                            <div class="sm:col-span-7 flex items-center justify-between sm:justify-start gap-4">
                                <div class="text-center">
                                    <div class="font-mono font-bold text-xl text-slate-900">{{ $flight->dep_time }}</div>
                                    <div class="font-mono text-xs font-semibold text-slate-500 bg-slate-100 px-2 py-0.5 rounded mt-0.5">{{ $flight->from_code }}</div>
                                </div>

                                <div class="flex-1 px-2 text-center">
                                    <div class="text-[11px] font-medium text-slate-500">{{ $flight->duration }}</div>
                                    <div class="relative flex items-center my-1">
                                        <div class="flex-grow border-t border-dashed border-slate-300"></div>
                                        <span class="mx-2 text-aero-sky text-xs">✈</span>
                                        <div class="flex-grow border-t border-dashed border-slate-300"></div>
                                    </div>
                                    <div class="text-[11px] font-semibold text-emerald-600">{{ $flight->type }}</div>
                                </div>

                                <div class="text-center">
                                    <div class="font-mono font-bold text-xl text-slate-900">{{ $flight->arr_time }}</div>
                                    <div class="font-mono text-xs font-semibold text-slate-500 bg-slate-100 px-2 py-0.5 rounded mt-0.5">{{ $flight->to_code }}</div>
                                </div>
                            </div>

                            <div class="sm:col-span-5 flex sm:flex-col items-center sm:items-end justify-between border-t sm:border-t-0 pt-3 sm:pt-0 border-slate-100">
                                <div class="text-left sm:text-right">
                                    <span class="text-[11px] text-slate-400 block">Harga dasar / org</span>
                                    <span class="font-heading font-extrabold text-xl text-aero-navy">Rp {{ number_format($flight->base_price, 0, ',', '.') }}</span>
                                    <span class="block text-[11px] text-emerald-600 font-medium">Gratis Bagasi {{ $flight->free_baggage }} Kg</span>
                                </div>
                                <button type="button" onclick="selectFlight(this)"
                                    class="mt-2 px-4 py-2 rounded-xl bg-aero-amber hover:bg-amber-400 text-slate-950 font-heading font-bold text-xs sm:text-sm transition shadow-sm">
                                    Pilih & Atur Kursi →
                                </button>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="bg-white rounded-2xl p-8 text-center border border-slate-200">
                        <p class="font-heading font-bold text-lg text-slate-800">Jadwal untuk rute tersebut belum tersedia</p>
                        <a href="{{ url('/') }}" class="inline-block mt-4 px-4 py-2 rounded-xl bg-aero-sky text-white text-sm font-semibold">Tampilkan Semua 12 Rute</a>
                    </div>
                @endforelse

                <!-- VOUCHER PROMO -->
                <div id="promo-member" class="pt-6">
                    <h3 class="font-heading font-bold text-lg text-slate-900 mb-3">Voucher Promo Member & Diskon Rute</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                        @foreach($promos as $promo)
                            <div class="bg-white rounded-xl p-4 border border-slate-200 shadow-sm flex flex-col justify-between">
                                <div>
                                    <div class="flex items-center justify-between gap-2 mb-1.5">
                                        <span class="font-mono text-xs font-bold px-2 py-0.5 rounded bg-amber-100 text-amber-900 border border-amber-300">
                                            {{ $promo->code }}
                                        </span>
                                        <span class="text-xs font-bold text-emerald-600">-Rp {{ number_format($promo->discount, 0, ',', '.') }}</span>
                                    </div>
                                    <h4 class="font-heading font-bold text-sm text-slate-800">{{ $promo->title }}</h4>
                                    <p class="text-xs text-slate-500 mt-1">{{ $promo->desc }}</p>
                                </div>
                                <button type="button"
                                    onclick="applyPromoCode('{{ $promo->code }}', {{ $promo->discount }}, {{ $promo->min_tx }})"
                                    class="mt-3 w-full py-1.5 rounded-lg bg-slate-100 hover:bg-aero-sky hover:text-white text-slate-700 font-semibold text-xs transition">
                                    Gunakan Kode
                                </button>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- PANEL KANAN: KUSTOMISASI & CHECKOUT -->
            <div class="lg:col-span-5">
                <div class="bg-white rounded-2xl border border-slate-200 shadow-xl p-5 sm:p-6 sticky top-20">
                    <div class="flex items-center justify-between pb-3 mb-4 border-b border-slate-200">
                        <div>
                            <span class="text-[11px] font-mono uppercase tracking-wider text-aero-sky font-bold">Interactive Ancillary Engine</span>
                            <h3 class="font-heading font-extrabold text-lg text-slate-900">Kustomisasi Tiket Anda</h3>
                        </div>
                        <span id="selectedFlightBadge" class="font-mono text-xs font-bold px-2.5 py-1 rounded bg-slate-100 text-slate-700">
                            Belum Dipilih
                        </span>
                    </div>

                    <div id="flightSummaryBox" class="p-3.5 rounded-xl bg-slate-50 border border-slate-200 mb-4 text-xs text-slate-600">
                        Silakan klik <strong>"Pilih & Atur Kursi"</strong> pada jadwal pesawat di sebelah kiri.
                    </div>

                    <!-- 1. BAGASI -->
                    <div class="mb-5">
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">
                            1. Tambahan Kuota Bagasi Tercatat
                        </label>
                        <select id="baggageSelect" onchange="recalculateTotal()" class="w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-xs sm:text-sm font-medium bg-white focus:outline-none focus:ring-2 focus:ring-aero-sky">
                            @foreach($baggageOptions as $bag)
                                <option value="{{ $bag['price'] }}" data-weight="{{ $bag['weight'] }}">
                                    {{ $bag['label'] }} ({{ $bag['price'] === 0 ? 'Gratis' : '+Rp ' . number_format($bag['price'], 0, ',', '.') }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- 2. PETA KURSI -->
                    <div class="mb-5">
                        <div class="flex items-center justify-between mb-2">
                            <label class="text-xs font-bold uppercase tracking-wider text-slate-700">
                                2. Pilih Nomor Kursi ({{ $passengers }} Penumpang)
                            </label>
                            <span class="font-mono text-xs text-aero-sky font-bold" id="selectedSeatsDisplay">Kursi: -</span>
                        </div>

                        <div class="flex items-center gap-4 text-[11px] text-slate-600 mb-2.5">
                            <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded bg-emerald-100 border border-emerald-400 inline-block"></span> Tersedia</span>
                            <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded bg-aero-amber border border-amber-600 inline-block"></span> Dipilih</span>
                            <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded bg-rose-100 border border-rose-300 inline-block"></span> Terisi</span>
                        </div>

                        <div class="bg-slate-50 border border-slate-200 rounded-xl p-3">
                            <div class="text-center text-[10px] font-mono uppercase tracking-widest text-slate-400 mb-2">▲ Kokpit Depan ▲</div>
                            <div id="seatMapContainer" class="space-y-1.5">
                                @foreach([1, 2, 3, 4, 5, 6] as $r)
                                    <div class="grid grid-cols-7 gap-1.5 items-center text-center">
                                        @foreach(['A', 'B', 'C'] as $c)
                                            @php $seatCode = $r . $c; @endphp
                                            <button type="button" data-seat="{{ $seatCode }}" onclick="toggleSeat('{{ $seatCode }}')"
                                                class="seat-btn py-1.5 rounded-lg font-mono text-xs font-bold border bg-emerald-50 border-emerald-300 text-emerald-900 hover:bg-emerald-100 transition">
                                                {{ $seatCode }}
                                            </button>
                                        @endforeach

                                        <div class="font-mono text-[10px] text-slate-400">{{ $r }}</div>

                                        @foreach(['D', 'E', 'F'] as $c)
                                            @php $seatCode = $r . $c; @endphp
                                            <button type="button" data-seat="{{ $seatCode }}" onclick="toggleSeat('{{ $seatCode }}')"
                                                class="seat-btn py-1.5 rounded-lg font-mono text-xs font-bold border bg-emerald-50 border-emerald-300 text-emerald-900 hover:bg-emerald-100 transition">
                                                {{ $seatCode }}
                                            </button>
                                        @endforeach
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- 3. KODE PROMO -->
                    <div class="mb-5">
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                            3. Voucher Promo Member
                        </label>
                        <div class="flex gap-2">
                            <input type="text" id="promoInput" placeholder="Ketik AEROMEMBER / klik kartu promo"
                                class="flex-1 rounded-xl border border-slate-300 px-3 py-2 text-xs font-mono uppercase focus:outline-none focus:ring-2 focus:ring-aero-sky">
                            <button type="button" onclick="manualPromoCheck()"
                                class="px-3.5 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-white text-xs font-semibold transition">
                                Pakai
                            </button>
                        </div>
                        <p id="promoFeedback" class="text-[11px] mt-1 text-slate-500"></p>
                    </div>

                    <!-- RINCIAN HARGA -->
                    <div class="border-t border-slate-200 pt-4 space-y-1.5 text-xs">
                        <div class="flex justify-between text-slate-600">
                            <span>Tiket Dasar ({{ $passengers }}x Penumpang)</span>
                            <span id="basePriceLabel" class="font-mono font-semibold">Rp 0</span>
                        </div>
                        <div class="flex justify-between text-slate-600">
                            <span>Tambahan Bagasi</span>
                            <span id="baggagePriceLabel" class="font-mono font-semibold">Rp 0</span>
                        </div>
                        <div class="flex justify-between text-emerald-600">
                            <span>Potongan Promo</span>
                            <span id="discountPriceLabel" class="font-mono font-semibold">-Rp 0</span>
                        </div>
                        <div class="flex justify-between items-center pt-2 border-t border-slate-100 text-sm font-heading font-extrabold text-slate-900">
                            <span>Total Pembayaran</span>
                            <span id="grandTotalLabel" class="text-lg text-aero-sky">Rp 0</span>
                        </div>
                    </div>

                    <!-- FORM SIMPAN KE DATABASE -->
                    <form method="POST" action="{{ url('/booking/store') }}" onsubmit="return validateSeatBeforeSubmit()" class="mt-4 space-y-3 border-t border-slate-200 pt-4">
                        @csrf
                        <input type="hidden" name="flight_id" id="inputFlightId">
                        <input type="hidden" name="flight_date" value="{{ $selectedDate }}">
                        <input type="hidden" name="pax_count" value="{{ $passengers }}">
                        <input type="hidden" name="selected_seats" id="inputSelectedSeats">
                        <input type="hidden" name="baggage_price" id="inputBaggagePrice" value="0">
                        <input type="hidden" name="extra_baggage_kg" id="inputBaggageKg" value="0">
                        <input type="hidden" name="promo_code" id="inputPromoCode">

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Nama Lengkap Penumpang</label>
                            <input type="text" name="passenger_name" required placeholder="Masukkan nama sesuai KTP/Paspor"
                                class="w-full rounded-xl border border-slate-300 px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-aero-sky">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Email Penerima E-Ticket</label>
                            <input type="email" name="passenger_email" required placeholder="nama@email.com"
                                class="w-full rounded-xl border border-slate-300 px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-aero-sky">
                        </div>

                        <button type="submit"
                            class="w-full py-3 px-4 rounded-xl bg-aero-amber hover:bg-amber-400 text-slate-950 font-heading font-extrabold text-sm shadow-md transition">
                            Simpan Pesanan & Kunci Kursi →
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- CEK PESANAN & REFUND -->
        <section id="cek-pesanan" class="mt-16 bg-white rounded-2xl border border-slate-200 p-6 sm:p-8 shadow-sm">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-6 items-center">
                <div class="md:col-span-5">
                    <span class="text-xs font-mono uppercase text-aero-sky font-bold">Self-Service Manage Booking</span>
                    <h3 class="font-heading font-extrabold text-xl text-slate-900 mt-1">Cek E-Ticket & Pengajuan Refund</h3>
                    <p class="text-xs sm:text-sm text-slate-600 mt-2">
                        Masukkan Kode Booking (PNR) 6 karakter untuk memeriksa status tiket atau membatalkan kursi secara otomatis.
                    </p>
                </div>
                <div class="md:col-span-7">
                    <form method="GET" action="{{ url('/') }}" class="flex flex-col sm:flex-row gap-3">
                        <input type="text" name="pnr" value="{{ request('pnr') }}" required placeholder="Masukkan Kode PNR (Contoh: AN892K)"
                            class="flex-1 rounded-xl border border-slate-300 px-3.5 py-2.5 text-sm font-mono uppercase focus:outline-none focus:ring-2 focus:ring-aero-sky">
                        <button type="submit" class="rounded-xl bg-aero-navy hover:bg-slate-800 text-white font-heading font-bold text-sm py-2.5 px-6 transition">
                            Cari PNR
                        </button>
                    </form>

                    @if($checkedBooking)
                        <div class="mt-4 p-4 rounded-xl bg-slate-50 border border-slate-200 text-xs space-y-2">
                            <div class="flex justify-between items-center border-b border-slate-200 pb-2">
                                <span class="font-mono font-bold text-sm text-aero-navy">PNR: {{ $checkedBooking->pnr_code }}</span>
                                <span class="px-2.5 py-0.5 rounded-full font-bold uppercase {{ $checkedBooking->status === 'confirmed' ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' }}">
                                    {{ $checkedBooking->status }}
                                </span>
                            </div>
                            <p><strong>Penumpang:</strong> {{ $checkedBooking->passenger_name }} ({{ $checkedBooking->passenger_email }})</p>
                            <p><strong>Penerbangan:</strong> {{ $checkedBooking->flight->airline }} ({{ $checkedBooking->flight->flight_no }}) — {{ $checkedBooking->flight->from_code }} → {{ $checkedBooking->flight->to_code }}</p>
                            <p><strong>Nomor Kursi:</strong> <span class="font-mono font-bold">{{ implode(', ', $checkedBooking->selected_seats ?? []) }}</span> | <strong>Bagasi Tambahan:</strong> +{{ $checkedBooking->extra_baggage_kg }} Kg</p>
                            <p><strong>Total Dibayar:</strong> <span class="font-mono font-bold text-aero-sky">Rp {{ number_format($checkedBooking->grand_total, 0, ',', '.') }}</span></p>

                            @if($checkedBooking->status === 'confirmed')
                                <form method="POST" action="{{ url('/booking/' . $checkedBooking->pnr_code . '/refund') }}" class="pt-2">
                                    @csrf
                                    <button type="submit" onclick="return confirm('Yakin ingin mengajukan refund? Kursi Anda akan dikosongkan kembali.')"
                                        class="px-4 py-2 rounded-lg bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs transition">
                                        Ajukan Refund & Lepas Kursi
                                    </button>
                                </form>
                            @endif
                        </div>
                    @elseif(request()->filled('pnr'))
                        <p class="mt-3 text-xs text-rose-600 font-semibold">Kode PNR "{{ request('pnr') }}" tidak ditemukan di database.</p>
                    @endif
                </div>
            </div>
        </section>
    </main>

    <!-- SCRIPT INTERAKTIF -->
    <script>
        const paxCount = Number(@json($passengers ?? 1)) || 1;
        const promosData = @json($promos ?? []);

        let currentFlight = null;
        let selectedSeats = [];
        let activePromo = { code: '', discount: 0, min_tx: 0 };

        function formatIDR(num) {
            return 'Rp ' + Number(num || 0).toLocaleString('id-ID');
        }

        function parseOccupiedSeats(raw) {
            if (!raw) return [];
            try {
                if (Array.isArray(raw)) return raw;
                const parsed = JSON.parse(raw);
                if (Array.isArray(parsed)) return parsed;
                if (typeof parsed === 'string') {
                    const doubleParsed = JSON.parse(parsed);
                    return Array.isArray(doubleParsed) ? doubleParsed : [];
                }
                return [];
            } catch (e) {
                return [];
            }
        }

        function swapAirports() {
            const fromEl = document.getElementById('fromSelect');
            const toEl = document.getElementById('toSelect');
            if (!fromEl || !toEl) return;

            const temp = fromEl.value;
            Array.from(toEl.options).forEach(opt => opt.disabled = false);
            Array.from(fromEl.options).forEach(opt => opt.disabled = false);

            fromEl.value = toEl.value;
            toEl.value = temp;
            syncAirportOptions();
        }

        function syncAirportOptions() {
            const fromEl = document.getElementById('fromSelect');
            const toEl = document.getElementById('toSelect');
            if (!fromEl || !toEl) return;

            const fromVal = fromEl.value;
            Array.from(toEl.options).forEach(opt => {
                if (opt.value !== "" && opt.value === fromVal) {
                    opt.disabled = true;
                    if (toEl.value === fromVal) {
                        toEl.value = "";
                    }
                } else {
                    opt.disabled = false;
                }
            });
        }

        function selectFlight(btnElement, shouldScroll = true) {
            const card = btnElement.closest('.flight-card');
            if (!card) return;

            document.querySelectorAll('.flight-card').forEach(c => {
                c.classList.remove('ring-2', 'ring-aero-sky', 'border-aero-sky', 'bg-sky-50/30');
                const btn = c.querySelector('button[onclick^="selectFlight"]');
                if (btn) {
                    btn.innerHTML = 'Pilih & Atur Kursi →';
                    btn.className = 'mt-2 px-4 py-2 rounded-xl bg-aero-amber hover:bg-amber-400 text-slate-950 font-heading font-bold text-xs sm:text-sm transition shadow-sm';
                }
            });

            card.classList.add('ring-2', 'ring-aero-sky', 'border-aero-sky', 'bg-sky-50/30');
            btnElement.innerHTML = '✓ Pesawat Terpilih';
            btnElement.className = 'mt-2 px-4 py-2 rounded-xl bg-emerald-600 text-white font-heading font-bold text-xs sm:text-sm transition shadow-sm';

            currentFlight = {
                id: card.dataset.id,
                airline: card.dataset.airline,
                flightNo: card.dataset.flightno,
                from: card.dataset.from,
                to: card.dataset.to,
                dep: card.dataset.dep,
                arr: card.dataset.arr,
                basePrice: parseInt(card.dataset.price || '0', 10),
                freeBag: card.dataset.freebag || '20',
                occupied: parseOccupiedSeats(card.dataset.occupied)
            };

            selectedSeats = [];
            document.getElementById('selectedFlightBadge').textContent = currentFlight.flightNo;
            document.getElementById('flightSummaryBox').innerHTML = `
                <div class="flex justify-between items-center">
                    <div>
                        <strong class="text-slate-900 font-heading">${currentFlight.airline} (${currentFlight.flightNo})</strong>
                        <div class="font-mono text-xs text-slate-500 mt-0.5">${currentFlight.from} (${currentFlight.dep}) → ${currentFlight.to} (${currentFlight.arr})</div>
                    </div>
                    <span class="text-emerald-700 bg-emerald-50 border border-emerald-200 px-2 py-0.5 rounded font-semibold">Bagasi Gratis ${currentFlight.freeBag} Kg</span>
                </div>
            `;

            renderSeatMap();
            recalculateTotal();

            const seatContainer = document.getElementById('seatMapContainer');
            if (shouldScroll && seatContainer) {
                seatContainer.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        }

        function renderSeatMap() {
            const buttons = document.querySelectorAll('.seat-btn');
            buttons.forEach(btn => {
                const code = btn.dataset.seat;
                const isOccupied = currentFlight && currentFlight.occupied.includes(code);
                const isSelected = selectedSeats.includes(code);

                btn.disabled = isOccupied;
                btn.className = 'seat-btn py-1.5 rounded-lg font-mono text-xs font-bold border transition ';

                if (isOccupied) {
                    btn.className += 'bg-rose-100 border-rose-300 text-rose-400 cursor-not-allowed line-through';
                } else if (isSelected) {
                    btn.className += 'bg-aero-amber border-amber-600 text-slate-950 shadow-sm scale-105';
                } else {
                    btn.className += 'bg-emerald-50 border-emerald-300 text-emerald-900 hover:bg-emerald-200 cursor-pointer';
                }
            });

            document.getElementById('selectedSeatsDisplay').textContent =
                selectedSeats.length > 0 ? 'Kursi: ' + selectedSeats.join(', ') : 'Kursi: -';
            document.getElementById('inputSelectedSeats').value = selectedSeats.join(',');
        }

        function toggleSeat(seatCode) {
            if (!currentFlight) {
                const firstBtn = document.querySelector('.flight-card button[onclick^="selectFlight"]');
                if (firstBtn) {
                    selectFlight(firstBtn, false);
                } else {
                    alert('Silakan pilih jadwal pesawat terlebih dahulu di sebelah kiri.');
                    return;
                }
            }

            if (currentFlight.occupied.includes(seatCode)) return;

            const idx = selectedSeats.indexOf(seatCode);
            if (idx > -1) {
                selectedSeats.splice(idx, 1);
            } else {
                if (selectedSeats.length >= paxCount) {
                    selectedSeats.shift();
                }
                selectedSeats.push(seatCode);
            }
            renderSeatMap();
        }

        function applyPromoCode(code, discount, minTx) {
            document.getElementById('promoInput').value = code;
            activePromo = { code, discount: Number(discount), min_tx: Number(minTx) };
            recalculateTotal();
        }

        function manualPromoCheck() {
            const inputCode = document.getElementById('promoInput').value.trim().toUpperCase();
            const found = promosData.find(p => p.code === inputCode);
            if (!found) {
                activePromo = { code: '', discount: 0, min_tx: 0 };
                document.getElementById('promoFeedback').textContent = 'Kode promo tidak ditemukan.';
                document.getElementById('promoFeedback').className = 'text-[11px] mt-1 text-rose-600 font-medium';
            } else {
                activePromo = { code: found.code, discount: Number(found.discount), min_tx: Number(found.min_tx) };
            }
            recalculateTotal();
        }

        function recalculateTotal() {
            const baseTotal = currentFlight ? currentFlight.basePrice * paxCount : 0;
            const bagSelect = document.getElementById('baggageSelect');
            const baggagePrice = parseInt(bagSelect.value || '0', 10);
            const baggageKg = parseInt(bagSelect.options[bagSelect.selectedIndex]?.dataset.weight || '0', 10);
            const subtotal = baseTotal + baggagePrice;

            let appliedDiscount = 0;
            const feedbackEl = document.getElementById('promoFeedback');

            if (activePromo.code) {
                if (subtotal >= activePromo.min_tx) {
                    appliedDiscount = activePromo.discount;
                    feedbackEl.textContent = `Kode ${activePromo.code} aktif! Hemat ${formatIDR(appliedDiscount)}.`;
                    feedbackEl.className = 'text-[11px] mt-1 text-emerald-600 font-semibold';
                } else {
                    feedbackEl.textContent = `Minimal transaksi ${formatIDR(activePromo.min_tx)} untuk kode ${activePromo.code}.`;
                    feedbackEl.className = 'text-[11px] mt-1 text-amber-600 font-medium';
                }
            }

            const grandTotal = Math.max(0, subtotal - appliedDiscount);

            document.getElementById('basePriceLabel').textContent = formatIDR(baseTotal);
            document.getElementById('baggagePriceLabel').textContent = formatIDR(baggagePrice);
            document.getElementById('discountPriceLabel').textContent = '-' + formatIDR(appliedDiscount);
            document.getElementById('grandTotalLabel').textContent = formatIDR(grandTotal);

            document.getElementById('inputFlightId').value = currentFlight ? currentFlight.id : '';
            document.getElementById('inputBaggagePrice').value = baggagePrice;
            document.getElementById('inputBaggageKg').value = baggageKg;
            document.getElementById('inputPromoCode').value = appliedDiscount > 0 ? activePromo.code : '';
        }

        function validateSeatBeforeSubmit() {
            if (!currentFlight) {
                alert('Pilih jadwal pesawat terlebih dahulu!');
                return false;
            }
            if (selectedSeats.length < paxCount) {
                alert(`Silakan klik ${paxCount} nomor kursi berwarna hijau pada peta kabin terlebih dahulu.`);
                return false;
            }
            return true;
        }

        window.addEventListener('DOMContentLoaded', () => {
            const fromEl = document.getElementById('fromSelect');
            if (fromEl) {
                fromEl.addEventListener('change', syncAirportOptions);
                syncAirportOptions();
            }

            const firstBtn = document.querySelector('.flight-card button[onclick^="selectFlight"]');
            if (firstBtn) selectFlight(firstBtn, false);
        });
    </script>
</body>
</html>