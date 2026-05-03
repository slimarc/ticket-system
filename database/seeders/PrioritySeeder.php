<?php

namespace Database\Seeders;

use App\Models\Priority;
use Illuminate\Database\Seeder;

class PrioritySeeder extends Seeder
{
    public function run(): void
    {
        $priorities = [
            ['name' => 'Low',      'estimated_hours' => 8, 'color' => 'green'],
            ['name' => 'Medium',   'estimated_hours' => 4, 'color' => 'yellow'],
            ['name' => 'High',     'estimated_hours' => 2, 'color' => 'orange'],
            ['name' => 'Critical', 'estimated_hours' => 1, 'color' => 'red'],
        ];

        foreach ($priorities as $priority) {
            Priority::firstOrCreate(['name' => $priority['name']], $priority);
        }
    }
}