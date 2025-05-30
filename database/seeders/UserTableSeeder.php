<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class UserTableSeeder extends Seeder
{
    /**
     * Jalankan proses seeding database.
     */
    public function run(): void
    {
        // Pastikan role admin sudah adaPada bagian awal method run(), kita memastikan bahwa role admin sudah ada di tabel roles dengan menggunakan Role::firstOrCreate(). Jika role admin belum ada, maka akan dibuat, jika sudah ada, akan diambil.
        $adminRole = Role::firstOrCreate(['name' => 'admin']); // Create or get the 'admin' role

        // Membuat user admin
        $user = User::create([
            'name'      => 'Administrator',
            'email'     => 'admin@gmail.com',
            'password'  => bcrypt('password'),
        ]);

        // Menambahkan role 'admin' ke user
        $user->assignRole($adminRole);
    }
}
