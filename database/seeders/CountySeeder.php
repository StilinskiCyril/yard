<?php

namespace Database\Seeders;

use App\Models\County;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CountySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $items = [
            'Baringo',
            'Bomet',
            'Bungoma',
            'Busia',
            'Elgeyo-Marakwet',
            'Embu',
            'Garissa',
            'Homa Bay',
            'Isiolo',
            'Kajiado',
            'Kakamega',
            'Kericho',
            'Kiambu',
            'Kilifi',
            'Kirinyaga',
            'Kisii',
            'Kisumu',
            'Kitui',
            'Kwale',
            'Laikipia',
            'Lamu',
            'Machakos',
            'Makueni',
            'Mandera',
            'Marsabit',
            'Meru',
            'Migori',
            'Mombasa',
            'Murang\'a',
            'Nairobi',
            'Nakuru',
            'Nandi',
            'Narok',
            'Nyamira',
            'Nyandarua',
            'Nyeri',
            'Samburu',
            'Siaya',
            'Taita-Taveta',
            'Tana River',
            'Tharaka-Nithi',
            'Trans Nzoia',
            'Turkana',
            'Uasin Gishu',
            'Vihiga',
            'Wajir',
            'West Pokot'
        ];

        foreach ($items as $item) {
            County::updateOrCreate([
                'name' => $item
            ]);
        }
    }
}
