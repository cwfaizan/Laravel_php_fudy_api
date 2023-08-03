<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Category::create([
            'title' => 'CHICKEN'
        ]);
        Category::create([
            'title' => 'PIZZA'
        ]);
        Category::create([
            'title' => 'BURGERS'
        ]);
        Category::create([
            'title' => 'DRINKS'
        ]);
        Category::create([
            'title' => 'SWEETS'
        ]);
        Category::create([
            'title' => 'SOMEWHAT SPECIAL'
        ]);
        Category::create([
            'title' => 'ADDONS'
        ]);
        Category::create([
            'title' => 'DEALS'
        ]);
    }
}
