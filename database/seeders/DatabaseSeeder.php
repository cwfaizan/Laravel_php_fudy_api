<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Category;
use App\Models\Order;
use App\Models\Recipe;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        Recipe::factory()->count(200)->create();
        $this->call(RoleSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(CategorySeeder::class);
        $this->call(MaizeSeeder::class);
        Order::factory()->count(200)->create();
        // Book::factory()->count(1000)->create();
        // $this->call(BookAuthorSeeder::class);
        // $this->call(BookEditionSeeder::class);
        Artisan::call('clear-compiled');
        Artisan::call('passport:install');
        Artisan::call('scribe:generate');
        Artisan::call('optimize:clear');
    }
}
