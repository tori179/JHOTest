<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ListSeeder extends Seeder
{
    public function run()
    {
        DB::table('lists')->insert([
            ['name' => 'Chị A', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'anh B', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}

