<?php

namespace Database\Seeders;

use App\Models\BodyType;
use App\Models\DriveSetup;
use App\Models\FuelType;
use App\Models\Make;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DriveSetupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $items = [
            'Left-Hand Drive',
            'Right-Hand Drive'
        ];

        foreach ($items as $item) {
            DriveSetup::updateOrCreate([
                'setup' => $item
            ]);
        }
    }
}
