<?php

namespace Database\Seeders;

use App\Models\Decision;
use Illuminate\Database\Seeder;

class DecisionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $decisions = [
            ['name' => '-'],
            ['name' => 'Disarankan'],
            ['name' => 'Netral'],
            ['name' => 'Tidak Disarankan'],
        ];
        foreach ($decisions as $decision) {
            Decision::create($decision);
        }
    }
}
