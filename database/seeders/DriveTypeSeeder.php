<?php

namespace Database\Seeders;

use App\Models\DriveType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DriveTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $items = [
            'FWD (Front Wheel Drive)',
            'RWD (Rear Wheel Drive)',
            '4WD (Four Wheel Drive)',
            'AWD (All Wheel Drive)'
        ];

        foreach ($items as $item) {
            DriveType::updateOrCreate([
                'type' => $item
            ]);
        }
    }
}
