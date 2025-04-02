<?php

namespace Database\Seeders;

use App\Models\Opportunity;
use Illuminate\Database\Seeder;

class OpportunitySeeder extends Seeder
{
    public function run()
    {
        Opportunity::create([
            'name' => 'Cơ hội hợp tác với ABC',
            'description' => 'Hợp tác với công ty ABC về phần mềm',
            'created_by' => 1, 
            'manager_id' => 1, 
            'stage_id' => 1 
        ]);

        Opportunity::create([
            'name' => 'Dự án phần mềm XYZ',
            'description' => 'Phát triển phần mềm cho công ty XYZ',
            'created_by' => 2,
            'manager_id' => 2,
            'stage_id' => 2
        ]);
    }
}

