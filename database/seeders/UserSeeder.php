<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::create([
            'name' => 'admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
        ]);
        $admin->assignRole('admin');

        $supplier = User::create([
            'name' => 'supplier',
            'email' => 'supplier@example.com',
            'password' => bcrypt('password'),
        ]);
        $supplier->assignRole('supplier');

        $penjaga = User::create([
            'name' => 'penjaga',
            'email' => 'penjaga@example.com',
            'password' => bcrypt('password'),
        ]);
        $penjaga->assignRole('penjaga lapak');

        $dropping = User::create([
            'name' => 'dropping',
            'email' => 'dropping@example.com',
            'password' => bcrypt('password'),
        ]);
        $dropping->assignRole('dropping');
    }
}
