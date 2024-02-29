<?php

namespace Database\Seeders;

use App\Models\Make;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MakeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $items = [
            'Toyota',
            'Honda',
            'Ford',
            'Chevrolet',
            'Nissan',
            'Volkswagen',
            'BMW',
            'Mercedes-Benz',
            'Audi',
            'Tesla',
            'Hyundai',
            'Kia',
            'Subaru',
            'Mazda',
            'Jeep',
            'Lexus',
            'Volvo',
            'Porsche',
            'Ferrari',
            'Lamborghini',
            'Maserati',
            'Bentley',
            'Rolls-Royce',
            'Jaguar',
            'Land Rover',
            'Aston Martin',
            'Bugatti',
            'Fiat',
            'Chrysler',
            'Dodge',
            'Buick',
            'Lincoln',
            'Cadillac',
            'GMC',
            'Acura',
            'Infiniti',
            'Mitsubishi',
            'Mini',
            'RAM',
            'McLaren',
            'Lotus',
            'Alfa Romeo',
            'Genesis',
            'Smart'
        ];

        foreach ($items as $item) {
            Make::updateOrCreate([
                'make' => $item
            ]);
        }
    }
}
