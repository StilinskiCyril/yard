<?php

namespace Database\Seeders;

use App\Models\BodyType;
use App\Models\Company;
use App\Models\CompanyUser;
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
        $existingAdmin = User::where('msisdn', '254705799644')->first();
        if(!$existingAdmin){
            $company = Company::create([
                'name' => "StilinskiYard",
                'msisdn' => '254705799644',
                'email' => 'aguvasucyril@gmail.com',
                'no_of_employees' => 20,
                'address' => 'Lower Kabete, Nairobi, Kenya',
            ]);

            $admin = User::create([
                'company_id' => $company->id,
                'name' => 'Cyril Aguvasu',
                'msisdn' => '254705799644',
                'msisdn_verified_at' => now(),
                'email' => 'aguvasucyril@gmail.com',
                'email_verified_at' => now(),
                'password' => Hash::make('aguvasucyril@gmail.com')
            ]);

            // Assign company to admin
            CompanyUser::create([
                'company_id' => $company->id,
                'user_id' => $admin->id,
            ]);

            $admin->assignRole('admin');
            $admin->assignRole('company-admin');
        }
    }
}
