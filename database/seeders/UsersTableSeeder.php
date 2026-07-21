<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Auto generated seed file
     */
    public function run()
    {
        \DB::table('users')->delete();

        \DB::table('users')->insert(array (
            0 =>
            array (
                'id' => '9104e031-2976-4d68-99f3-1d57214beb18',
                'name' => 'Admin SAKINAH',
                'username' => 'admin',
                'email_verified_at' => NULL,
                'password' => Hash::make('admin123'),
                'current_role' => 'bcd0b4ce-2cf8-4fc4-af88-4573c59a2377',
                'is_active' => true,
                'remember_token' => NULL,
                'created_at' => now(),
                'updated_at' => now(),
            ),
            1 =>
            array (
                'id' => '296c478c-3e40-4d05-83f5-a1e97e92aaf5',
                'name' => 'Superadmin SAKINAH',
                'username' => 'superadmin',
                'email_verified_at' => now(),
                'password' => Hash::make('admin123'),
                'current_role' => 'e4510500-70b4-44a9-968c-3e66fecc6fb9',
                'is_active' => true,
                'remember_token' => NULL,
                'created_at' => now(),
                'updated_at' => now(),
            ),
        ));
    }
}
