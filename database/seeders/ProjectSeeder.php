<?php

namespace Database\Seeders;

use App\Models\Project;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProjectSeeder extends Seeder
{
    public function run(): void
    {
        $rows = [
            [
                'title' => 'Panel MV Substation Oil & Gas Site',
                'short_description' => 'Instalasi panel Medium Voltage untuk substation di lapangan operasi minyak & gas dengan konfigurasi ATV6000 dan SM AirSeT.',
                'description' => "Proyek turnkey untuk substation MV di lokasi lapangan minyak dan gas.\n\nPT. Radika Bintang Nusantara mengerjakan design engineering, supply panel, commissioning, dan hand-over. Menggunakan Altivar Process ATV6000 untuk drive pompa injeksi dan SM AirSeT sebagai switchgear utama.\n\nSelesai on-time tanpa gangguan produksi.",
                'client' => 'Oil & Gas Operator',
                'location' => 'Kalimantan Timur, Indonesia',
                'year' => 2025,
            ],
            [
                'title' => 'Water Treatment Plant Variable Speed Drive Retrofit',
                'short_description' => 'Retrofit VSD ATV600 untuk 12 pompa air baku pada plant WTP kapasitas 500 L/s.',
                'description' => "Modernisasi sistem kontrol pompa Water Treatment Plant dari drive lama ke Altivar Process ATV600 untuk efisiensi energi.\n\nMencakup engineering, supply, panel installation, dan integrasi ke SCADA existing. Efisiensi energi meningkat rata-rata 22% pada 3 bulan pertama operasi.",
                'client' => 'PDAM Regional',
                'location' => 'Jawa Timur, Indonesia',
                'year' => 2025,
            ],
            [
                'title' => 'Mining Conveyor Automation Upgrade',
                'short_description' => 'Upgrade sistem otomasi conveyor 3 km dengan ATV900 & PLC Modicon M580 untuk operasi tambang batubara.',
                'description' => "Upgrade menyeluruh sistem drive dan otomasi conveyor sepanjang 3 km di lokasi tambang batubara.\n\nMenggunakan ATV900 untuk load sharing 8 motor drive dan PLC M580 ePAC sebagai central controller. Mengurangi downtime dari rata-rata 8 jam/bulan menjadi kurang dari 1 jam/bulan.",
                'client' => 'Coal Mining Company',
                'location' => 'Kalimantan Selatan, Indonesia',
                'year' => 2025,
            ],
            [
                'title' => 'Panel LV Building Management System',
                'short_description' => 'Suplai dan instalasi panel LV untuk gedung perkantoran 12 lantai lengkap dengan monitoring EcoStruxure.',
                'description' => "Panel LV terintegrasi untuk gedung perkantoran modern 12 lantai.\n\nMencakup ACB Masterpact, MCCB Compact NSX, PLC untuk Building Management System, dan HMI touchscreen di ruang panel utama. Terintegrasi dengan platform EcoStruxure Power Monitoring Expert.",
                'client' => 'Property Developer',
                'location' => 'Surabaya, Jawa Timur',
                'year' => 2025,
            ],
            [
                'title' => 'Trafo Distribusi Pabrik Makanan',
                'short_description' => 'Suplai transformer Trafindo 1600 kVA untuk pabrik pengolahan makanan skala industri.',
                'description' => "Suplai dan instalasi trafo distribusi Trafindo 1600 kVA (20/0.4 kV) untuk pabrik pengolahan makanan.\n\nProject scope termasuk pemilihan spesifikasi, pengiriman on-site, connection dan commissioning. Cocok untuk load dinamis khas industri Food & Beverages.",
                'client' => 'Food Processing Plant',
                'location' => 'Sidoarjo, Jawa Timur',
                'year' => 2025,
            ],
            [
                'title' => 'SCADA Integration Multi-Site Substation',
                'short_description' => 'Integrasi AVEVA Plant SCADA untuk monitoring 5 substation distribusi listrik industrial estate.',
                'description' => "Integrasi platform AVEVA Plant SCADA untuk monitoring dan kontrol terpusat atas 5 substation distribusi listrik pada kawasan industri.\n\nMengurangi waktu response event dari 30 menit menjadi kurang dari 5 menit. Menyediakan trending, alarming, dan reporting harian ke operator.",
                'client' => 'Industrial Estate Manager',
                'location' => 'Gresik, Jawa Timur',
                'year' => 2025,
            ],
        ];

        foreach ($rows as $i => $row) {
            Project::updateOrCreate(
                ['slug' => Str::slug($row['title'])],
                array_merge($row, [
                    'position' => $i,
                    'is_published' => true,
                ])
            );
        }
    }
}
