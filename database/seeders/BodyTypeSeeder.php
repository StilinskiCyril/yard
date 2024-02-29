<?php

namespace Database\Seeders;

use App\Models\BodyType;
use App\Models\FuelType;
use App\Models\Make;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BodyTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $items = [
            'Sedan',
            'SUV',
            'Hatchback',
            'Coupe',
            'Convertible',
            'Pickup Truck',
            'Van/Mini-Van',
            'Crossover',
            'Wagon',
            'Limousine',
            'Truck'
        ];

        foreach ($items as $item) {
            BodyType::updateOrCreate([
                'type' => $item
            ]);
        }
    }
}
