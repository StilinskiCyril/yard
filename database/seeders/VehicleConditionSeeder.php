<?php

namespace Database\Seeders;

use App\Models\BodyType;
use App\Models\DriveSetup;
use App\Models\FuelType;
use App\Models\Make;
use App\Models\VehicleCondition;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VehicleConditionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $items = [
            'Brand-New',
            'Foreign-Used',
            'Local-Used'
        ];

        foreach ($items as $item) {
            VehicleCondition::updateOrCreate([
                'condition' => $item
            ]);
        }
    }
}
