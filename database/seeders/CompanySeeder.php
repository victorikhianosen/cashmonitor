<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CompanySeeder extends Seeder
{
    public function run(): void
    {
        DB::table('companies')->insert([
            'name' => 'Fake Company Ltd',
            'slug' => Str::slug('Fake Company Ltd'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
