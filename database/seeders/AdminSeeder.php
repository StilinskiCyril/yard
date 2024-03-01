<?php

namespace Database\Seeders;

use App\Models\BodyType;
use App\Models\FuelType;
use App\Models\Make;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $existing_admin = User::where('msisdn', '254705799644')->first();
        if(!$existing_admin){
            $admin = User::create([
                'name' => 'Cyril Aguvasu',
                'msisdn' => '254705799644',
                'msisdn_verified_at' => now(),
                'email' => 'admin@gmail.com',
                'email_verified_at' => now(),
                'password' => Hash::make('admin@gmail.com')
            ]);
            $admin->assignRole('admin');
        }
    }
}
