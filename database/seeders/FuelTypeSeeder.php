<?php

namespace Database\Seeders;

use App\Models\FuelType;
use App\Models\Make;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FuelTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $items = [
            'Petrol',
            'Diesel',
            'Electric',
            'Hybric'
        ];

        foreach ($items as $item) {
            FuelType::updateOrCreate([
                'type' => $item
            ]);
        }
    }
}
