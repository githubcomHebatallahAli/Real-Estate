<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PropertySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('properties')->insert([
            'type' => 'Flat',
        ]);

        DB::table('properties')->insert([
            'type' => 'Land',
        ]);

        DB::table('properties')->insert([
            'type' => 'House',
        ]);

        DB::table('properties')->insert([
            'type' => 'Villa',
        ]);

        DB::table('properties')->insert([
            'type' => 'Chalet',
        ]);

        DB::table('properties')->insert([
            'type' => 'Shop',
        ]);

        DB::table('properties')->insert([
            'type' => 'Clinic',
        ]);

        DB::table('properties')->insert([
            'type' => 'Office',
        ]);
    }
}
