<?php

namespace Database\Seeders;

use App\Models\FuelType;
use App\Models\Make;
use App\Models\TransmissionType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TransmissionTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $items = [
            'Automatic',
            'Manual',
            'Semi-Automatic'
        ];

        foreach ($items as $item) {
            TransmissionType::updateOrCreate([
                'type' => $item
            ]);
        }
    }
}
