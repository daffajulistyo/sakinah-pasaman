<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    public function run()
    {
        \DB::table('roles')->delete();

        \DB::table('roles')->insert(array (
            array (
                'id' => 'e4510500-70b4-44a9-968c-3e66fecc6fb9',
                'role_name' => 'Superadmin',
                'role_desc' => 'Super Administrator',
                'type' => 'core',
                'created_by' => 'system',
                'updated_by' => NULL,
                'created_at' => now(),
                'updated_at' => now(),
            ),
            array (
                'id' => 'bcd0b4ce-2cf8-4fc4-af88-4573c59a2377',
                'role_name' => 'Admin',
                'role_desc' => 'Administrator',
                'type' => 'core',
                'created_by' => 'system',
                'updated_by' => NULL,
                'created_at' => now(),
                'updated_at' => now(),
            ),
            array (
                'id' => '00f34f96-1111-4111-8111-000000000001',
                'role_name' => 'Admin_KDH',
                'role_desc' => 'Admin Kepala Daerah',
                'type' => 'common',
                'created_by' => 'system',
                'updated_by' => NULL,
                'created_at' => now(),
                'updated_at' => now(),
            ),
            array (
                'id' => 'cdb1d545-1111-4111-8111-000000000002',
                'role_name' => 'Admin_OPD',
                'role_desc' => 'Admin Perangkat Daerah',
                'type' => 'common',
                'created_by' => 'system',
                'updated_by' => NULL,
                'created_at' => now(),
                'updated_at' => now(),
            ),
            array (
                'id' => '39d57ab8-1111-4111-8111-000000000003',
                'role_name' => 'Pegawai',
                'role_desc' => 'Pegawai / ASN',
                'type' => 'common',
                'created_by' => 'system',
                'updated_by' => NULL,
                'created_at' => now(),
                'updated_at' => now(),
            ),
        ));
    }
}
