<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class UserSakipTableSeeder extends Seeder
{
    public function run()
    {
        \DB::table('user_sakip')->delete();

        $opd = \DB::table('master_opd')->first();

        \DB::table('user_sakip')->insert([
            [
                'id' => Str::uuid(),
                'user_id' => '9104e031-2976-4d68-99f3-1d57214beb18',
                'master_opd_id' => $opd->id,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'user_id' => '296c478c-3e40-4d05-83f5-a1e97e92aaf5',
                'master_opd_id' => $opd->id,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
