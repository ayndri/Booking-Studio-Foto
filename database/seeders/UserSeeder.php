<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Membuat user default untuk admin dan owner.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@upfoto.test'],
            [
                'name' => 'Administrator',
                'role' => User::ROLE_ADMIN,
                'password' => Hash::make('admin12345'),
            ]
        );

        User::updateOrCreate(
            ['email' => 'owner@upfoto.test'],
            [
                'name' => 'Owner Studio',
                'role' => User::ROLE_OWNER,
                'password' => Hash::make('owner12345'),
            ]
        );
    }
}
