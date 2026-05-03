<?php

namespace Database\Seeders;

use App\Models\Sector;
use Illuminate\Database\Seeder;

class SectorSeeder extends Seeder
{
    public function run(): void
    {
        $sectors = [
            ['name' => 'IT',         'description' => 'Information Technology'],
            ['name' => 'HR',         'description' => 'Human Resources'],
            ['name' => 'Finance',    'description' => 'Financial department'],
            ['name' => 'Operations', 'description' => 'Operational support'],
        ];

        foreach ($sectors as $sector) {
            Sector::firstOrCreate(['name' => $sector['name']], $sector);
        }
    }
}