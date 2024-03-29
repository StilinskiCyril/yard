<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        $this->call(RoleSeeder::class);
        $this->call(CountySeeder::class);
        $this->call(MakeSeeder::class);
        $this->call(FuelTypeSeeder::class);
        $this->call(TransmissionTypeSeeder::class);
        $this->call(CurrencySeeder::class);
        $this->call(BodyTypeSeeder::class);
        $this->call(DriveSetupSeeder::class);
        $this->call(DriveTypeSeeder::class);
        $this->call(VehicleConditionSeeder::class);
        $this->call(SettingSeeder::class);
        $this->call(AdminSeeder::class);
    }
}
