<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (!(DB::table('roles')->where('name', '=', 'admin')->exists())) {
            Role::create([
                'name' => 'customer'
            ]);
            Role::create([

                'name' => 'waiter'

            ]);
            Role::create([
                'name' => 'seller'
            ]);
            Role::create([
                'name' => 'admin'
            ]);
        }
    }
}
