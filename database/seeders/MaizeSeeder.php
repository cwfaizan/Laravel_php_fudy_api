<?php

namespace Database\Seeders;

use App\Models\Maize;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MaizeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Maize::create([
            'name' => 'A1'
        ]);
        Maize::create([
            'name' => 'A2'
        ]);
        Maize::create([
            'name' => 'B1'
        ]);
        Maize::create([
            'name' => 'B2'
        ]);
        Maize::create([
            'name' => 'C1'
        ]);
        Maize::create([
            'name' => 'C2'
        ]);
        Maize::create([
            'name' => 'D1'
        ]);
        Maize::create([
            'name' => 'D2'
        ]);
        Maize::create([
            'name' => 'E1'
        ]);
        Maize::create([
            'name' => 'E2'
        ]);
        Maize::create([
            'name' => 'F1'
        ]);
        Maize::create([
            'name' => 'F2'
        ]);
    }
}
