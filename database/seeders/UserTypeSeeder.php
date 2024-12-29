<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('usertypes')->insert([
            'userType' => 'User',
        ]);

        DB::table('usertypes')->insert([
            'userType' => 'Broker',
        ]);
    }
}
