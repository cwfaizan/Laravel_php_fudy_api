<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (!(DB::table('users')->where('name', 'Admin')->exists())) {
            DB::transaction(function () {
                $user = User::Create([
                    'name' => 'Admin',
                    'contact_no' => '923456789000',
                    'contact_no_verified' => 1,
                    'email' => 'admin@demo.com',
                    'email_verified' => 1,
                    'password' => Hash::make(12345678),
                    'active' => 1,
                ]);
                $user->roles()->attach(Role::where([['name', 'admin']])->get(['id']));

                $user = User::Create([
                    'name' => 'Seller',
                    'contact_no' => '923456789001',
                    'contact_no_verified' => 1,
                    'email' => 'seller@demo.com',
                    'email_verified' => 1,
                    'password' => Hash::make(12345678),
                    'active' => 1,
                ]);
                $user->roles()->attach(Role::where([['name', 'seller']])->get(['id']));

                $user = User::Create([
                    'name' => 'waiter',
                    'contact_no' => '923456789002',
                    'contact_no_verified' => 1,
                    'email' => 'waiter@demo.com',
                    'email_verified' => 1,
                    'password' => Hash::make(12345678),
                    'active' => 1,
                ]);
                $user->roles()->attach(Role::where([['name', 'waiter']])->get(['id']));

                $user = User::Create([
                    'name' => 'customer',
                    'contact_no' => '923456789003',
                    'contact_no_verified' => 1,
                    'email' => 'customer@demo.com',
                    'email_verified' => 1,
                    'password' => Hash::make(12345678),
                    'active' => 1,
                ]);
                $user->roles()->attach(Role::where([['name', 'customer']])->get(['id']));
            });
        }
    }
}
