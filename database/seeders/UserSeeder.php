<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        $companyId = DB::table('companies')->value('id');
        $branchId = DB::table('branches')->value('id');

        foreach (range(1, 20) as $index) {
            DB::table('users')->insert([
                'company_id' => $companyId,
                'branch_id' => $branchId,
                'name' => $faker->name, // ✅ Random realistic name
                'role' => $faker->randomElement(['admin', 'manager', 'staff']),
                'email' => 'admin' . $index . '@gmail.com', // unique emails for seeding
                'email_verified_at' => now(),
                'password' => Hash::make('12345678'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
