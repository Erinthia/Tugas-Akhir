<?php

namespace Database\Seeders;

use App\Models\Division;
use Illuminate\Database\Seeder;

class DivisionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $divisions = [
            ['name' => 'Administration', 'description' => 'Administration division'],
            ['name' => 'Finance', 'description' => 'Finance division'],
            ['name' => 'Human Resource', 'description' => 'Human Resource division'],
            ['name' => 'Marketing', 'description' => 'Marketing division'],
            ['name' => 'Production', 'description' => 'Production division'],
            ['name' => 'Research and Development', 'description' => 'Research and Development division'],
            ['name' => 'Sales', 'description' => 'Sales division'],
            ['name' => 'Supply Chain', 'description' => 'Supply Chain division'],
            ['name' => 'Information Technology', 'description' => 'Information Technology division'],
        ];

        foreach ($divisions as $division) {
            Division::create($division);
        }
    }
}
