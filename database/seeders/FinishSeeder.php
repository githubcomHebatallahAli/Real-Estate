<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class FinishSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('finishes')->insert([
            'type' => 'Finished',
        ]);

        DB::table('finishes')->insert([
            'type' => 'semiFinished',
        ]);

        DB::table('finishes')->insert([
            'type' => 'notFinished',
        ]);


    }
}
