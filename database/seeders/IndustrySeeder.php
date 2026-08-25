<?php

namespace Database\Seeders;

use App\Models\Industry;
use App\Models\Service;
use Illuminate\Database\Seeder;

class IndustrySeeder extends Seeder
{
    public function run(): void
    {
        $industriesWithServices = [
            [
                'name' => 'Jewellery & Ornaments',
                'services' => [
                    ['name' => '3D CAD Designing', 'description' => 'Jewellery 3D CAD modeling and rendering'],
                    ['name' => 'Metal Casting (Gold/Silver)', 'description' => 'Precision metal casting services'],
                    ['name' => 'Stone Setting & Polishing', 'description' => 'Gemstone setting, filing, and final buffing'],
                    ['name' => 'Laser Soldering & Welding', 'description' => 'Micro laser welding and repair'],
                ],
            ],
            [
                'name' => 'Textile & Garments',
                'services' => [
                    ['name' => 'Fabric Dyeing & Printing', 'description' => 'Digital and screen fabric printing'],
                    ['name' => 'Custom Embroidery', 'description' => 'Computerized multi-head embroidery'],
                    ['name' => 'Pattern Cutting & Stitching', 'description' => 'Garment tailoring and batch stitching'],
                ],
            ],
            [
                'name' => 'Metal & Manufacturing',
                'services' => [
                    ['name' => 'CNC Machining & Lathe Work', 'description' => 'Precision CNC milling and turning'],
                    ['name' => 'Powder Coating & Anodizing', 'description' => 'Industrial metal surface coating'],
                    ['name' => 'Sheet Metal Fabrication', 'description' => 'Laser cutting, bending, and welding'],
                ],
            ],
            [
                'name' => 'Electronics & Hardware',
                'services' => [
                    ['name' => 'PCB Assembly & Soldering', 'description' => 'SMT and THT component assembly'],
                    ['name' => 'Component Testing & Repair', 'description' => 'Electronic circuit diagnostic testing'],
                ],
            ],
        ];

        foreach ($industriesWithServices as $indData) {
            $industry = Industry::firstOrCreate(
                ['name' => $indData['name']],
                ['status' => true]
            );

            foreach ($indData['services'] as $servData) {
                Service::firstOrCreate(
                    [
                        'industry_id' => $industry->id,
                        'name' => $servData['name'],
                    ],
                    [
                        'description' => $servData['description'],
                    ]
                );
            }
        }
    }
}
