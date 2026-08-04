<?php

namespace Database\Seeders;

use App\Models\Project;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProjectSeeder extends Seeder
{
    public function run(): void
    {
        $projects = [
            [
                'title' => 'Modernisasi Gardu Distribusi 20 kV',
                'title_en' => '20 kV Distribution Substation Modernization',
                'category' => 'Oil & Gas',
                'category_en' => 'Oil & Gas',
                'client' => 'PT. Migas Nusantara',
                'location' => 'Balikpapan, Kalimantan Timur',
                'year' => 2024,
                'summary' => 'Peremajaan panel MV, retrofit switchgear Schneider Electric SM6, dan integrasi sistem monitoring untuk area distribusi utama kilang.',
                'summary_en' => 'MV panel refurbishment, Schneider Electric SM6 switchgear retrofit, and monitoring system integration for the refinery main distribution area.',
                'description' => "Proyek modernisasi mencakup penggantian panel medium voltage 20 kV eksisting dengan Schneider Electric SM6, integrasi RTU untuk telemetri SCADA, serta commissioning dan pelatihan operator selama dua minggu.\n\nHasil akhir memberi peningkatan reliability index gardu sebesar 30% dan mengurangi waktu switching operation dari 15 menit menjadi kurang dari 2 menit.",
                'is_featured' => true,
            ],
            [
                'title' => 'Sistem Kelistrikan Gedung Perkantoran 12 Lantai',
                'title_en' => 'Electrical System for a 12-Story Office Building',
                'category' => 'Building',
                'category_en' => 'Building',
                'client' => 'PT. Griya Investama',
                'location' => 'Surabaya, Jawa Timur',
                'year' => 2024,
                'summary' => 'Desain dan supply sistem distribusi LV, transformator kering 1250 kVA, capacitor bank, serta EcoStruxure Power Monitoring Expert.',
                'summary_en' => 'Design and supply of the LV distribution system, 1250 kVA dry transformer, capacitor bank, and EcoStruxure Power Monitoring Expert.',
                'description' => "Menyediakan solusi paket kelistrikan turnkey mulai dari studi beban, panel LV, MDP/SDP, transformator, hingga aplikasi monitoring energi berbasis EcoStruxure.\n\nPenerapan capacitor bank meningkatkan power factor dari 0.79 menjadi 0.98 dan mengurangi tagihan listrik bulanan sekitar 12%.",
                'is_featured' => true,
            ],
            [
                'title' => 'Elektrifikasi Area Tambang Nikel',
                'title_en' => 'Nickel Mining Site Electrification',
                'category' => 'Mining',
                'category_en' => 'Mining',
                'client' => 'PT. Sulawesi Nikel Perkasa',
                'location' => 'Morowali, Sulawesi Tengah',
                'year' => 2023,
                'summary' => 'Supply container substation 2 x 2500 kVA dan medium voltage switchgear untuk feeding conveyor & processing plant.',
                'summary_en' => 'Supply of 2 × 2500 kVA container substations and medium voltage switchgear feeding the conveyor and processing plant.',
                'description' => "Solusi container substation ready-to-install untuk mempercepat commissioning di area remote. Dilengkapi arc-flash protection dan sensor thermal untuk keamanan operator 24/7.\n\nProyek diselesaikan 2 minggu lebih cepat dari jadwal, memangkas biaya sewa genset sementara.",
                'is_featured' => false,
            ],
            [
                'title' => 'Panel Kontrol Instalasi Pengolahan Air',
                'title_en' => 'Water Treatment Plant Control Panel',
                'category' => 'Water Segment',
                'category_en' => 'Water Segment',
                'client' => 'PDAM Kabupaten Sidoarjo',
                'location' => 'Sidoarjo, Jawa Timur',
                'year' => 2025,
                'summary' => 'Panel kontrol pompa dan dosing dengan PLC Modicon M241, HMI Magelis, serta variable speed drive Altivar 320 untuk optimasi energi.',
                'summary_en' => 'Pump and dosing control panel with Modicon M241 PLC, Magelis HMI, and Altivar 320 variable speed drives for energy optimization.',
                'description' => "Desain panel kontrol lengkap dengan integrasi SCADA lokal. Sistem menggunakan Altivar 320 untuk soft-start pompa sehingga memperpanjang usia motor dan menghemat energi hingga 22%.\n\nProyek dilengkapi maintenance contract satu tahun serta remote support.",
                'is_featured' => false,
            ],
            [
                'title' => 'Retrofit Panel MCC Pabrik Makanan',
                'title_en' => 'Food Factory MCC Panel Retrofit',
                'category' => 'Food & Beverages',
                'category_en' => 'Food & Beverages',
                'client' => 'PT. Sari Boga Makmur',
                'location' => 'Pasuruan, Jawa Timur',
                'year' => 2023,
                'summary' => 'Retrofit Motor Control Center dengan TeSys GV & LC dan modul komunikasi Modbus untuk 32 motor produksi.',
                'summary_en' => 'Motor Control Center retrofit with TeSys GV & LC and Modbus communication modules for 32 production motors.',
                'description' => "Penggantian starter konvensional dengan TeSys GV/LC memberi proteksi motor yang lebih akurat dan trace ability event melalui komunikasi Modbus ke sistem MES eksisting.\n\nKegiatan dilakukan bertahap agar tidak mengganggu jalur produksi.",
                'is_featured' => false,
            ],
            [
                'title' => 'Automasi Booster Pump Gedung Rumah Sakit',
                'title_en' => 'Hospital Booster Pump Automation',
                'category' => 'Building',
                'category_en' => 'Building',
                'client' => 'RS. Cahaya Sehat',
                'location' => 'Malang, Jawa Timur',
                'year' => 2024,
                'summary' => 'Panel automasi booster pump dengan redundansi lead-lag, sensor tekanan, dan monitoring 24 jam via EcoStruxure Facility Expert.',
                'summary_en' => 'Booster pump automation panel with lead-lag redundancy, pressure sensors, and 24/7 monitoring via EcoStruxure Facility Expert.',
                'description' => "Sistem menjamin ketersediaan air bertekanan konstan di semua lantai dengan pola operasi lead-lag antar pompa. Notifikasi anomali dikirim ke tim maintenance melalui aplikasi mobile.\n\nEnergi listrik pompa turun 18% berkat pola operasi bertahap.",
                'is_featured' => false,
            ],
        ];

        foreach ($projects as $index => $data) {
            $data['slug'] = Str::slug($data['title']);
            $data['position'] = $index;
            Project::updateOrCreate(['slug' => $data['slug']], $data);
        }
    }
}
