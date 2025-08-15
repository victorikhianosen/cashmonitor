<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BranchSeeder extends Seeder
{
    public function run(): void
    {
        $companyId = DB::table('companies')->value('id');

        DB::table('branches')->insert([
            'company_id' => $companyId,
            'name' => 'Head Office',
            'account_number' => '5000117972',
            'slug' => Str::slug('Head Office'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
