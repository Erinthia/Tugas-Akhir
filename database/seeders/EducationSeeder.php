<?php

namespace Database\Seeders;

use App\Models\Education;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class EducationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $educations = [
            ['name' => 'Tidak Bersekolah'],
            ['name' => 'D3'],
            ['name' => 'D4'],
            ['name' => 'S1'],
            ['name' => 'S2'],
            ['name' => 'S3'],
        ];

        foreach ($educations as $item) {
            Education::create($item);
        }
    }
}
