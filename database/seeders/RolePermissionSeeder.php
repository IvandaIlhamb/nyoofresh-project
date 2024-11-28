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
        Permission::create(['name'=>'user']);
        Permission::create(['name'=>'monitoringrekapdropping']);
        Permission::create(['name'=>'monitoringrekaplapak']);
        Permission::create(['name'=>'suplai']);
        Permission::create(['name'=>'gajimodalpengeluaran']);
        Permission::create(['name'=>'pemasukan']);

        // ------supplier
        Permission::create(['name'=>'produk']);
        Permission::create(['name'=>'rekappenjualan']);
        Permission::create(['name'=>'suplaiharian']);

        // ------penjaga lapak
        Permission::create(['name'=>'hasilpenjualan']);
        Permission::create(['name'=>'pengeluaran']);
        // ------dropping
        Permission::create(['name'=>'dropping']);

        // -----role name
        Role::create(['name'=>'admin']);
        Role::create(['name'=>'supplier']);
        Role::create(['name'=>'penjaga lapak']);
        Role::create(['name'=>'dropping']);

        $roleAdmin = Role::findByName('admin');
        $roleAdmin->givePermissionTo('user');
        $roleAdmin->givePermissionTo('produk');
        $roleAdmin->givePermissionTo('monitoringrekapdropping');
        $roleAdmin->givePermissionTo('monitoringrekaplapak');
        $roleAdmin->givePermissionTo('suplai');
        $roleAdmin->givePermissionTo('gajimodalpengeluaran');
        $roleAdmin->givePermissionTo('pemasukan');


        $roleSupplier = Role::findByName('supplier');
        $roleSupplier->givePermissionTo('produk');
        $roleSupplier->givePermissionTo('rekappenjualan');
        $roleSupplier->givePermissionTo('suplai');

        $rolePenjaga = Role::findByName('penjaga lapak');
        $rolePenjaga->givePermissionTo('hasilpenjualan');
        $rolePenjaga->givePermissionTo('pengeluaran');

        $roleDropping = Role::findByName('dropping');
        $roleDropping->givePermissionTo('hasilpenjualan');
        $roleDropping->givePermissionTo('pengeluaran');
        $roleDropping->givePermissionTo('dropping');
    }
}
