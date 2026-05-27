<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $categories = ProductCategory::pluck('id', 'slug');

        $products = [
            [
                'category' => 'distribution-network',
                'name' => 'Power System Distribution Network',
                'short_description' => 'Solusi jaringan distribusi primer & sekunder lengkap untuk tegangan 7.2 kV sampai 132 kV.',
                'full_description' => '<p>Power System Offer menghadirkan rangkaian lengkap solusi distribusi tenaga listrik mulai dari Primary Distribution Network hingga Secondary Distribution Network. Cakupan tegangan mulai dari <strong>7.2 kV hingga 132 kV</strong>, mendukung transformer station, switching substation, ring main unit, hingga metering.</p><p>Dirancang untuk keandalan tinggi pada aplikasi industri Oil &amp; Gas, Mining, Building, hingga Water Segment dengan dukungan engineering dan integrasi sistem dari PT. Radika Bintang Nusantara.</p>',
                'specifications' => [
                    'Voltage Range' => '7.2 kV - 132 kV',
                    'Network Type' => 'Primary & Secondary Distribution',
                    'Application' => 'Substation, Switching, Metering',
                ],
                'features' => [
                    'Cakupan tegangan luas 7.2 kV hingga 132 kV',
                    'Solusi primary & secondary distribution terintegrasi',
                    'Keandalan tinggi untuk aplikasi industri kritikal',
                    'Didukung engineering & system integration',
                ],
                'is_featured' => false,
            ],
            [
                'category' => 'altivar-process-atv6000',
                'name' => 'ATV6000',
                'short_description' => 'Medium Voltage Variable Speed Drive 2.4 kV - 13.8 kV hingga 20 MW untuk aplikasi industri berat.',
                'full_description' => '<p>Altivar Process ATV6000 adalah Medium Voltage Variable Speed Drive yang melengkapi rangkaian Altivar Process dengan solusi untuk aplikasi medium voltage sesuai kebutuhan operasional Anda.</p><p>ATV6000 menawarkan cakupan lengkap rentang tegangan dari <strong>2.4 kV hingga 13.8 kV</strong> dan daya hingga <strong>20 MW</strong>. Umumnya digunakan pada aplikasi pompa, kipas, kompresor, crusher, dan conveyor di industri Mining &amp; Minerals, Oil &amp; Gas, Water &amp; Wastewater, serta Power Generation. Teknologi Low Harmonic Multi-Pulse / Non-Regen membantu menjaga kualitas daya.</p>',
                'specifications' => [
                    'Voltage Range' => '2.4 kV - 13.8 kV',
                    'Power Range' => 'Up to 20 MW',
                    'Technology' => 'Low Harmonic Multi-Pulse / Non-Regen',
                    'Application' => 'Pumps, Fans, Compressors, Crushers, Conveyors',
                ],
                'features' => [
                    'Cakupan tegangan 2.4 kV hingga 13.8 kV',
                    'Daya hingga 20 MW',
                    'Low Harmonic Multi-Pulse / Non-Regen',
                    'Optimal untuk Mining, Oil & Gas, Water & Power Generation',
                    'Manajemen energi & pengoptimalan proses',
                ],
                'is_featured' => true,
            ],
            [
                'category' => 'altivar-process-atv600-atv900',
                'name' => 'ATV600',
                'short_description' => 'Variable Speed Drive 0.75 kW - 800 kW untuk fluid management dengan layanan EcoStruxure.',
                'full_description' => '<p>Altivar Process ATV600 adalah variable speed drive untuk fluid management dengan rentang daya <strong>0.75 kW hingga 800 kW</strong>. Dilengkapi embedded services yang didedikasikan untuk memproses industri dan utilitas pada aplikasi 3-phase synchronous, asynchronous, dan motor khusus mulai dari 0.75 kW sampai 1.5 MW.</p><p>Terintegrasi dengan EcoStruxure untuk monitoring dan efisiensi operasional pada aplikasi Fluid &amp; Gas Handling seperti Fan, Centrifugal, dan Compressor.</p>',
                'specifications' => [
                    'Power Range' => '0.75 kW - 800 kW',
                    'Motor Type' => '3-Phase Synchronous / Asynchronous',
                    'Platform' => 'EcoStruxure',
                    'Application' => 'Fluid & Gas Handling (Fan, Centrifugal, Compressor)',
                ],
                'features' => [
                    'Booster & Level Control',
                    'Pump Characteristic & Flow Estimation',
                    'DP/Head Correction',
                    'Pump Start/Stop & Pipe Fill',
                    'Jockey Pump & Priming Pump Control',
                    'Flow Limitation',
                ],
                'is_featured' => true,
            ],
            [
                'category' => 'altivar-process-atv600-atv900',
                'name' => 'ATV900',
                'short_description' => 'Variable Speed Drive untuk solid & mechanical handling seperti rod, mixer, dan hoist.',
                'full_description' => '<p>Altivar Process ATV900 adalah variable speed drive yang dirancang untuk aplikasi <strong>Solid &amp; Mechanical Handling</strong> seperti rod, mixer, dan hoist. Mendukung integrasi cabinet dengan rentang daya luas hingga 800 kW dan opsi wall mounting maupun cabinet integration.</p><p>Dilengkapi fungsi-fungsi canggih untuk aplikasi handling yang menuntut presisi dan keandalan tinggi.</p>',
                'specifications' => [
                    'Power Range' => 'Up to 800 kW',
                    'Mounting' => 'Wall Mounting / Cabinet Integration',
                    'Protection' => 'UL Type 1 / IP55',
                    'Application' => 'Solid & Mechanical Handling (Rod, Mixer, Hoist)',
                ],
                'features' => [
                    'Master/Slave & Load Sharing',
                    'Backlash Sequence',
                    'Hoisting Function & High Speed Hoisting',
                    'Brake Logic Control',
                    'Rope Slack Handling',
                    'Conveyor Function',
                ],
                'is_featured' => false,
            ],
            [
                'category' => 'sm-airset',
                'name' => 'SM AirSeT',
                'short_description' => 'MV Switchgear bebas gas SF6 dengan shunt vacuum interruption untuk distribusi listrik berkelanjutan.',
                'full_description' => '<p>SM AirSeT adalah Medium Voltage Switchgear bebas gas SF6 yang membuat distribusi listrik lebih berkelanjutan. Menggunakan udara murni (pure air) sebagai isolasi dan teknologi shunt vacuum interruption sebagai pengganti gas SF6.</p><p>Dilengkapi ready-to-board design, thermal monitoring, cloud-enabled monitoring, QR code, internal arc detection, dan LIDAR untuk meningkatkan keandalan dan keselamatan operasional sekaligus mengurangi dampak lingkungan.</p>',
                'specifications' => [
                    'Insulation' => 'SF6-Free (Pure Air)',
                    'Interruption' => 'Shunt Vacuum Interruption',
                    'Monitoring' => 'Thermal & Cloud-Enabled',
                    'Safety' => 'Internal Arc Detection',
                ],
                'features' => [
                    'Bebas gas SF6 - ramah lingkungan',
                    'Ready-to-board design',
                    'Thermal & cloud-enabled monitoring',
                    'QR code untuk akses informasi cepat',
                    'Internal arc detection & LIDAR',
                ],
                'is_featured' => true,
            ],
            [
                'category' => 'panel-cubicle-sm6-24',
                'name' => 'SM6-24 Cubicle',
                'short_description' => 'Modular Switchboard Air-Insulated hingga 24 kV, mudah dipasang dan dioperasikan.',
                'full_description' => '<p>SM6-24 Cubicle adalah Modular Switchboard hingga <strong>24 kV</strong> dengan teknologi Air-Insulated. Switchboard modular ini menjamin keandalan untuk distribusi sekunder ground-mounted, fleksibel, serta mudah dipasang dan dioperasikan.</p><p>Tersedia dalam berbagai konfigurasi fungsi unit: IM, IMC, DM1-A, DM1-D, QM, dan QMC untuk memenuhi beragam kebutuhan aplikasi distribusi.</p>',
                'specifications' => [
                    'Voltage' => 'Up to 24 kV',
                    'Insulation' => 'Air-Insulated (AIS)',
                    'Application' => 'Ground-Mounted Secondary Distribution',
                    'Units' => 'IM, IMC, DM1-A, DM1-D, QM, QMC',
                ],
                'features' => [
                    'Modular hingga 24 kV',
                    'Air-insulated, fleksibel & andal',
                    'Mudah dipasang dan dioperasikan',
                    'Beragam konfigurasi unit fungsi',
                ],
                'is_featured' => true,
            ],
            [
                'category' => 'panel-cubicle-sm6-24',
                'name' => 'DM1-A Circuit Breaker Unit',
                'short_description' => 'Single-isolation, disconnectable circuit breaker unit untuk panel cubicle MV.',
                'full_description' => '<p>DM1-A adalah unit Single-Isolation, Disconnectable Circuit Breaker yang menjadi bagian dari rangkaian Panel Cubicle. Cocok untuk aplikasi proteksi feeder pada distribusi medium voltage.</p>',
                'specifications' => [
                    'Type' => 'Single-Isolation Disconnectable',
                    'Function' => 'Circuit Breaker Unit',
                    'Voltage' => 'Up to 24 kV',
                ],
                'features' => [
                    'Single-isolation disconnectable',
                    'Proteksi feeder yang andal',
                    'Kompatibel dengan panel cubicle SM6',
                ],
                'is_featured' => false,
            ],
            [
                'category' => 'lv-product',
                'name' => 'LV Product Range',
                'short_description' => 'Rangkaian produk Low Voltage: ACB, MCCB, MCB, Contactor, HMI, PLC, UPS dan lainnya.',
                'full_description' => '<p>Rangkaian lengkap produk Low Voltage untuk kebutuhan proteksi, kontrol, dan otomasi. Mencakup Air Circuit Breaker (ACB), Moulded Case Circuit Breaker (MCCB), Miniature Circuit Breaker (MCB), Contactor, HMI, PLC, dan UPS.</p><p>Mendukung pembangunan panel dan sistem otomasi untuk berbagai aplikasi industri dan building.</p>',
                'specifications' => [
                    'Category' => 'Low Voltage',
                    'Components' => 'ACB, MCCB, MCB, Contactor',
                    'Automation' => 'HMI, PLC, UPS',
                ],
                'features' => [
                    'ACB, MCCB, MCB untuk proteksi',
                    'Contactor untuk switching beban',
                    'HMI & PLC untuk otomasi',
                    'UPS untuk keandalan daya',
                ],
                'is_featured' => false,
            ],
            [
                'category' => 'aveva-plant-scada',
                'name' => 'AVEVA Plant SCADA',
                'short_description' => 'Industrial software SCADA untuk monitoring dan kontrol plant secara real-time.',
                'full_description' => '<p>AVEVA Plant SCADA adalah perangkat lunak industri untuk supervisory control and data acquisition (SCADA). Membantu mengurangi waktu rekayasa (engineering), meningkatkan efisiensi operator, serta meningkatkan produktivitas dan output produksi.</p><p>Dilengkapi visualisasi data real-time, alarm management, dan integrasi sistem untuk pengelolaan plant yang lebih cerdas.</p>',
                'specifications' => [
                    'Type' => 'Industrial SCADA Software',
                    'Benefit' => 'Reduced Engineering Time',
                    'Focus' => 'Operator Efficiency & Productivity',
                ],
                'features' => [
                    'Mengurangi waktu rekayasa',
                    'Meningkatkan efisiensi operator',
                    'Meningkatkan produktivitas & output produksi',
                    'Visualisasi data real-time',
                ],
                'is_featured' => false,
            ],
            [
                'category' => 'busway-cable-management',
                'name' => 'Busway & Cable Management',
                'short_description' => 'Sistem busway dan manajemen kabel untuk distribusi daya yang rapi dan efisien.',
                'full_description' => '<p>Solusi Busway dan Cable Management untuk distribusi daya yang efisien, aman, dan rapi. Cocok untuk aplikasi building dan industri dengan kebutuhan distribusi daya fleksibel serta perawatan yang mudah.</p>',
                'specifications' => [
                    'Type' => 'Busway System',
                    'Function' => 'Power Distribution & Cable Management',
                    'Application' => 'Building & Industrial',
                ],
                'features' => [
                    'Distribusi daya efisien',
                    'Instalasi rapi & fleksibel',
                    'Perawatan mudah',
                ],
                'is_featured' => false,
            ],
            [
                'category' => 'trafo',
                'name' => 'Trafindo Transformer',
                'short_description' => 'Distribution transformer Trafindo dengan kapasitas 200 - 6000 kVA.',
                'full_description' => '<p>Trafindo Transformer adalah distribution transformer dengan kapasitas mulai <strong>200 kVA hingga 6000 kVA</strong>. Dirancang untuk keandalan dan efisiensi tinggi pada aplikasi distribusi tenaga listrik industri dan building.</p>',
                'specifications' => [
                    'Capacity' => '200 kVA - 6000 kVA',
                    'Brand' => 'Trafindo',
                    'Type' => 'Distribution Transformer',
                ],
                'features' => [
                    'Kapasitas 200 - 6000 kVA',
                    'Keandalan & efisiensi tinggi',
                    'Untuk aplikasi industri & building',
                ],
                'is_featured' => true,
            ],
        ];

        foreach ($products as $product) {
            Product::updateOrCreate(
                ['slug' => Str::slug($product['name'])],
                [
                    'product_category_id' => $categories[$product['category']],
                    'name' => $product['name'],
                    'short_description' => $product['short_description'],
                    'full_description' => $product['full_description'],
                    'specifications' => $product['specifications'],
                    'features' => $product['features'],
                    'is_featured' => $product['is_featured'],
                ]
            );
        }
    }
}
