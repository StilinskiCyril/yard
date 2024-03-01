<?php

namespace Database\Seeders;

use Spatie\Permission\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $items = [
            'admin', // super admin
            'customer-support', // customer support
            'company-admin', // company admin
            'company-user', // company user
            'user' // buyers
        ];

        foreach ($items as $item) {
            Role::updateOrCreate([
                'name' => $item
            ]);
        }
    }
}
