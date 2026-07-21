<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class RoleplayTableSeeder extends Seeder
{
    public function run()
    {
        \DB::table('roleplay')->delete();

        \DB::table('roleplay')->insert(array (
            array (
                'id' => Str::uuid(),
                'user_id' => '296c478c-3e40-4d05-83f5-a1e97e92aaf5',
                'role_id' => 'e4510500-70b4-44a9-968c-3e66fecc6fb9',
                'type' => 'core',
                'created_by' => 'system',
                'updated_by' => NULL,
                'created_at' => now(),
                'updated_at' => now(),
            ),
            array (
                'id' => Str::uuid(),
                'user_id' => '9104e031-2976-4d68-99f3-1d57214beb18',
                'role_id' => 'bcd0b4ce-2cf8-4fc4-af88-4573c59a2377',
                'type' => 'core',
                'created_by' => 'system',
                'updated_by' => NULL,
                'created_at' => now(),
                'updated_at' => now(),
            ),
            array (
                'id' => Str::uuid(),
                'user_id' => '9104e031-2976-4d68-99f3-1d57214beb18',
                'role_id' => 'e4510500-70b4-44a9-968c-3e66fecc6fb9',
                'type' => 'core',
                'created_by' => 'system',
                'updated_by' => NULL,
                'created_at' => now(),
                'updated_at' => now(),
            ),
            array (
                'id' => Str::uuid(),
                'user_id' => '9104e031-2976-4d68-99f3-1d57214beb18',
                'role_id' => '00f34f96-1111-4111-8111-000000000001',
                'type' => 'common',
                'created_by' => 'system',
                'updated_by' => NULL,
                'created_at' => now(),
                'updated_at' => now(),
            ),
            array (
                'id' => Str::uuid(),
                'user_id' => '9104e031-2976-4d68-99f3-1d57214beb18',
                'role_id' => 'cdb1d545-1111-4111-8111-000000000002',
                'type' => 'common',
                'created_by' => 'system',
                'updated_by' => NULL,
                'created_at' => now(),
                'updated_at' => now(),
            ),
            array (
                'id' => Str::uuid(),
                'user_id' => '9104e031-2976-4d68-99f3-1d57214beb18',
                'role_id' => '39d57ab8-1111-4111-8111-000000000003',
                'type' => 'common',
                'created_by' => 'system',
                'updated_by' => NULL,
                'created_at' => now(),
                'updated_at' => now(),
            ),
        ));
    }
}
