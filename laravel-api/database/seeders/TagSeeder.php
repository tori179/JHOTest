<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TagSeeder extends Seeder
{
    public function run()
    {
        DB::table('tags')->insert([
            ['name' => 'cơ hội 1', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'cơ hội 2', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}

