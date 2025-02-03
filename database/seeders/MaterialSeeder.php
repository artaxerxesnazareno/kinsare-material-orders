<?php

namespace Database\Seeders;

use App\Models\Material;
use Illuminate\Database\Seeder;

class MaterialSeeder extends Seeder
{
    public function run(): void
    {
        $materials = [
            [
                'name' => 'Papel A4',
                'price' => 25.90
            ],
            [
                'name' => 'Caneta Esferográfica',
                'price' => 1.50
            ],
            [
                'name' => 'Lápis',
                'price' => 0.90
            ],
            [
                'name' => 'Borracha',
                'price' => 0.50
            ],
            [
                'name' => 'Caderno',
                'price' => 15.90
            ],
            [
                'name' => 'Pasta',
                'price' => 8.90
            ],
            [
                'name' => 'Grampeador',
                'price' => 12.90
            ],
            [
                'name' => 'Clips',
                'price' => 3.50
            ]
        ];

        foreach ($materials as $material) {
            Material::create($material);
        }
    }
}
