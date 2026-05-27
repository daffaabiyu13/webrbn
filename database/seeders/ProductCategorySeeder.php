<?php

namespace Database\Seeders;

use App\Models\ProductCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Distribution Network',
                'description' => 'Solusi jaringan distribusi tegangan primer & sekunder dari 7.2 kV hingga 132 kV.',
                'icon' => 'bolt',
            ],
            [
                'name' => 'Altivar Process ATV6000',
                'description' => 'Medium Voltage Variable Speed Drive 2.4 kV - 13.8 kV hingga 20 MW.',
                'icon' => 'cpu',
            ],
            [
                'name' => 'Altivar Process ATV600 & ATV900',
                'description' => 'Variable Speed Drive untuk fluid handling dan solid & mechanical handling.',
                'icon' => 'gauge',
            ],
            [
                'name' => 'SM AirSeT',
                'description' => 'Medium Voltage Switchgear bebas gas SF6 untuk distribusi listrik berkelanjutan.',
                'icon' => 'shield',
            ],
            [
                'name' => 'Panel Cubicle SM6-24',
                'description' => 'Modular Switchboard Air-Insulated hingga 24 kV.',
                'icon' => 'server',
            ],
            [
                'name' => 'LV Product',
                'description' => 'ACB, MCCB, MCB, Contactor, HMI, PLC, UPS dan komponen Low Voltage lainnya.',
                'icon' => 'plug',
            ],
            [
                'name' => 'AVEVA Plant SCADA',
                'description' => 'Industrial software SCADA untuk monitoring dan kontrol plant secara real-time.',
                'icon' => 'monitor',
            ],
            [
                'name' => 'Busway & Cable Management',
                'description' => 'Sistem busway dan manajemen kabel untuk distribusi daya yang efisien.',
                'icon' => 'cable',
            ],
            [
                'name' => 'Trafo',
                'description' => 'Transformer Trafindo dengan kapasitas 200 - 6000 kVA.',
                'icon' => 'transformer',
            ],
        ];

        foreach ($categories as $category) {
            ProductCategory::updateOrCreate(
                ['slug' => Str::slug($category['name'])],
                [
                    'name' => $category['name'],
                    'description' => $category['description'],
                    'icon' => $category['icon'],
                ]
            );
        }
    }
}
