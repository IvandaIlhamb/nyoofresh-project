<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // ------admin or super user
        Permission::create(['name'=>'add-user']);
        Permission::create(['name'=>'edit-user']);
        Permission::create(['name'=>'delete-user']);
        Permission::create(['name'=>'show-user']);

        // ------supplier
        Permission::create(['name'=>'add-produk']);
        Permission::create(['name'=>'salling-recap']);
        Permission::create(['name'=>'daily-suply']);

        // ------penjaga lapak
        Permission::create(['name'=>'hasil-penjualan']);
        Permission::create(['name'=>'pengeluaran']);
        // ------dropping
        Permission::create(['name'=>'dropping']);

        // -----role name
        Role::create(['name'=>'admin']);
        Role::create(['name'=>'supplier']);
        Role::create(['name'=>'penjaga lapak']);
        Role::create(['name'=>'dropping']);

        $roleAdmin = Role::findByName('admin');
        $roleAdmin->givePermissionTo('add-user');
        $roleAdmin->givePermissionTo('edit-user');
        $roleAdmin->givePermissionTo('delete-user');
        $roleAdmin->givePermissionTo('show-user');

        $roleSupplier = Role::findByName('supplier');
        $roleSupplier->givePermissionTo('add-produk');
        $roleSupplier->givePermissionTo('salling-recap');
        $roleSupplier->givePermissionTo('daily-suply');

        $rolePenjaga = Role::findByName('penjaga lapak');
        $rolePenjaga->givePermissionTo('hasil-penjualan');
        $rolePenjaga->givePermissionTo('pengeluaran');

        $roleDropping = Role::findByName('dropping');
        $roleDropping->givePermissionTo('hasil-penjualan');
        $roleDropping->givePermissionTo('pengeluaran');
        $roleDropping->givePermissionTo('dropping');
    }
}
