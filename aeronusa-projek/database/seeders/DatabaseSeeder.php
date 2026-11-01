<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Airport;
use App\Models\Flight;
use App\Models\Promo;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Seed 10 Bandara Populer
        $airports = [
            ['code' => 'CGK', 'city' => 'Jakarta', 'name' => 'Soekarno-Hatta Intl', 'zone' => 'WIB'],
            ['code' => 'DPS', 'city' => 'Denpasar (Bali)', 'name' => 'I Gusti Ngurah Rai Intl', 'zone' => 'WITA'],
            ['code' => 'SUB', 'city' => 'Surabaya', 'name' => 'Juanda Intl', 'zone' => 'WIB'],
            ['code' => 'YIA', 'city' => 'Yogyakarta', 'name' => 'Yogyakarta Intl', 'zone' => 'WIB'],
            ['code' => 'KNO', 'city' => 'Medan', 'name' => 'Kualanamu Intl', 'zone' => 'WIB'],
            ['code' => 'UPG', 'city' => 'Makassar', 'name' => 'Sultan Hasanuddin Intl', 'zone' => 'WITA'],
            ['code' => 'BPN', 'city' => 'Balikpapan', 'name' => 'Sultan Aji Muhammad Sulaiman', 'zone' => 'WITA'],
            ['code' => 'SRG', 'city' => 'Semarang', 'name' => 'Jenderal Ahmad Yani', 'zone' => 'WIB'],
            ['code' => 'PLM', 'city' => 'Palembang', 'name' => 'Sultan Mahmud Badaruddin II', 'zone' => 'WIB'],
            ['code' => 'LOP', 'city' => 'Lombok', 'name' => 'Zainuddin Abdul Madjid', 'zone' => 'WITA'],
        ];
        foreach ($airports as $ap) {
            Airport::updateOrCreate(['code' => $ap['code']], $ap);
        }

        // 2. Seed 12 Rute Jadwal Pesawat Aktif
        $flights = [
            [
                'flight_no' => 'GN-402', 'airline' => 'Garuda Nusantara', 'aircraft' => 'Boeing 737-800 NG',
                'from_code' => 'CGK', 'to_code' => 'DPS', 'dep_time' => '07:30', 'arr_time' => '10:25',
                'duration' => '1j 55m', 'type' => 'Langsung', 'class' => 'Ekonomi', 'base_price' => 1150000,
                'free_baggage' => 20, 'badge' => 'Paling Tepat Waktu (96%)', 'occupied_seats' => ['1B', '1C', '2A', '3D']
            ],
            [
                'flight_no' => 'AN-718', 'airline' => 'AeroNusa Citilink', 'aircraft' => 'Airbus A320neo',
                'from_code' => 'DPS', 'to_code' => 'CGK', 'dep_time' => '14:15', 'arr_time' => '15:10',
                'duration' => '1j 55m', 'type' => 'Langsung', 'class' => 'Ekonomi', 'base_price' => 920000,
                'free_baggage' => 15, 'badge' => 'Rute Balik Populer', 'occupied_seats' => ['1A', '2B', '3A', '5D']
            ],
            [
                'flight_no' => 'PS-209', 'airline' => 'Pelita Sky', 'aircraft' => 'Airbus A320-200',
                'from_code' => 'CGK', 'to_code' => 'SUB', 'dep_time' => '13:00', 'arr_time' => '14:30',
                'duration' => '1j 30m', 'type' => 'Langsung', 'class' => 'Ekonomi', 'base_price' => 760000,
                'free_baggage' => 20, 'badge' => 'Rekomendasi Populer', 'occupied_seats' => ['1D', '2E', '4C']
            ],
            [
                'flight_no' => 'PS-210', 'airline' => 'Pelita Sky', 'aircraft' => 'Airbus A320-200',
                'from_code' => 'SUB', 'to_code' => 'CGK', 'dep_time' => '16:00', 'arr_time' => '17:30',
                'duration' => '1j 30m', 'type' => 'Langsung', 'class' => 'Ekonomi', 'base_price' => 750000,
                'free_baggage' => 20, 'badge' => 'Jadwal Sore Favorit', 'occupied_seats' => ['2C', '3B', '5A']
            ],
            [
                'flight_no' => 'GN-510', 'airline' => 'Garuda Nusantara', 'aircraft' => 'Boeing 737-800 NG',
                'from_code' => 'CGK', 'to_code' => 'YIA', 'dep_time' => '08:45', 'arr_time' => '10:00',
                'duration' => '1j 15m', 'type' => 'Langsung', 'class' => 'Ekonomi', 'base_price' => 720000,
                'free_baggage' => 20, 'badge' => 'In-Flight Meal', 'occupied_seats' => ['1A', '3C']
            ],
            [
                'flight_no' => 'BA-612', 'airline' => 'Batik Aero', 'aircraft' => 'Boeing 737-900ER',
                'from_code' => 'CGK', 'to_code' => 'KNO', 'dep_time' => '09:15', 'arr_time' => '11:35',
                'duration' => '2j 20m', 'type' => 'Langsung', 'class' => 'Ekonomi', 'base_price' => 1320000,
                'free_baggage' => 20, 'badge' => 'In-Flight Entertainment', 'occupied_seats' => ['2A', '2B', '4D']
            ],
            [
                'flight_no' => 'GN-620', 'airline' => 'Garuda Nusantara', 'aircraft' => 'Airbus A330-300',
                'from_code' => 'CGK', 'to_code' => 'UPG', 'dep_time' => '06:30', 'arr_time' => '09:55',
                'duration' => '2j 25m', 'type' => 'Langsung', 'class' => 'Bisnis', 'base_price' => 1850000,
                'free_baggage' => 30, 'badge' => 'Kabin Wide-Body', 'occupied_seats' => ['1A', '1F', '2D']
            ],
            [
                'flight_no' => 'AN-405', 'airline' => 'AeroNusa Citilink', 'aircraft' => 'Airbus A320neo',
                'from_code' => 'CGK', 'to_code' => 'BPN', 'dep_time' => '11:00', 'arr_time' => '14:15',
                'duration' => '2j 15m', 'type' => 'Langsung', 'class' => 'Ekonomi', 'base_price' => 1240000,
                'free_baggage' => 15, 'badge' => 'Akses IKN Nusantara', 'occupied_seats' => ['3A', '3B', '6C']
            ],
            [
                'flight_no' => 'BA-881', 'airline' => 'Batik Aero', 'aircraft' => 'Airbus A320-200',
                'from_code' => 'SUB', 'to_code' => 'DPS', 'dep_time' => '16:20', 'arr_time' => '18:15',
                'duration' => '0j 55m', 'type' => 'Langsung', 'class' => 'Ekonomi', 'base_price' => 590000,
                'free_baggage' => 20, 'badge' => 'Rute Cepat 55 Menit', 'occupied_seats' => ['1C', '4E']
            ],
            [
                'flight_no' => 'AN-312', 'airline' => 'AeroNusa Citilink', 'aircraft' => 'Airbus A320-200',
                'from_code' => 'YIA', 'to_code' => 'DPS', 'dep_time' => '12:10', 'arr_time' => '14:30',
                'duration' => '1j 20m', 'type' => 'Langsung', 'class' => 'Ekonomi', 'base_price' => 690000,
                'free_baggage' => 15, 'badge' => 'Rute Wisata Budaya', 'occupied_seats' => ['2A', '5B', '5C']
            ],
            [
                'flight_no' => 'PS-114', 'airline' => 'Pelita Sky', 'aircraft' => 'Airbus A320-200',
                'from_code' => 'CGK', 'to_code' => 'PLM', 'dep_time' => '07:00', 'arr_time' => '08:05',
                'duration' => '1j 05m', 'type' => 'Langsung', 'class' => 'Ekonomi', 'base_price' => 610000,
                'free_baggage' => 20, 'badge' => 'Penerbangan Pagi', 'occupied_seats' => ['1B', '3E']
            ],
            [
                'flight_no' => 'GN-708', 'airline' => 'Garuda Nusantara', 'aircraft' => 'Boeing 737-800 NG',
                'from_code' => 'DPS', 'to_code' => 'LOP', 'dep_time' => '15:00', 'arr_time' => '15:45',
                'duration' => '0j 45m', 'type' => 'Langsung', 'class' => 'Ekonomi', 'base_price' => 480000,
                'free_baggage' => 20, 'badge' => 'Island Hopping', 'occupied_seats' => ['2D', '4A']
            ],
        ];
        foreach ($flights as $fl) {
            Flight::updateOrCreate(['flight_no' => $fl['flight_no']], $fl);
        }

        // 3. Seed Promo Member
        $promos = [
            ['code' => 'AEROMEMBER', 'title' => 'Diskon Member Eksklusif', 'discount' => 150000, 'min_tx' => 800000, 'desc' => 'Potongan Rp 150.000 khusus member'],
            ['code' => 'TERBANGHEMAT', 'title' => 'Promo Terbang Pagi', 'discount' => 75000, 'min_tx' => 500000, 'desc' => 'Potongan Rp 75.000 semua rute'],
            ['code' => 'BALIESCAPE', 'title' => 'Spesial Liburan Bali', 'discount' => 200000, 'min_tx' => 1200000, 'desc' => 'Potongan Rp 200.000 min. transaksi 1,2 Juta'],
        ];
        foreach ($promos as $pr) {
            Promo::updateOrCreate(['code' => $pr['code']], $pr);
        }
    }
}