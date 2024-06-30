<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // buat sebuah role berdasarkan aktor
        // buat role owner atau pemilik dari toko
        $ownerRole = Role::create([
            'name' => 'owner'
        ]);

        // buat role buyer atau pelanggan dari toko
        $buyerRole = Role::create([
            'name' => 'buyer'
        ]);

        // buat generate otomatis satu user ketika migrasi
        $user = User::create([
            'name' => 'Fany Pemilik',
            'email' => 'fany@owner.com',
            'password' => bcrypt('123123123')
        ]);

        // buat si Fany menjadi owner
        $user->assignRole($ownerRole);
    }
}
