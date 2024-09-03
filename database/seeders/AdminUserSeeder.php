<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        User::updateOrCreate(
            ['email' => 'd@email.com'],
            [
                'name' => 'dralve',
                'email' => 'd@email.com',
                'password' => Hash::make('11223344'),
                'role' => 'admin',
            ]
        );
    }
}
