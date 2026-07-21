<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class MasterOpdTableSeeder extends Seeder
{
    public function run()
    {
        \DB::table('master_opd')->delete();

        \DB::table('master_opd')->insert([
            [
                'id' => 'd1e2f3a4-1111-4111-8111-000000000001',
                'kode_opd' => '001',
                'nama_opd' => 'DINAS KOMUNIKASI DAN INFORMATIKA',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
