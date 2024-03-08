<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $items = [
            ['name' => 'Card Order Upload Fee', 'value' => 5000]
        ];

        foreach ($items as $item) {
            Setting::updateOrCreate(['name' => $item['name']], ['value' => $item['value']]);
        }
    }
}
