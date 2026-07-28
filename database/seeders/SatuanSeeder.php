<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SatuanSeeder extends Seeder
{
    public function run(): void
    {
        $username = 'admin';
        $now = now();

        $satuan = [
            ['id' => Str::uuid(), 'satuan' => 'Persen (%)', 'keterangan' => 'Persentase', 'is_active' => true, 'created_by' => $username, 'created_at' => $now, 'updated_at' => $now],
            ['id' => Str::uuid(), 'satuan' => 'Nilai', 'keterangan' => 'Nilai Absolut', 'is_active' => true, 'created_by' => $username, 'created_at' => $now, 'updated_at' => $now],
            ['id' => Str::uuid(), 'satuan' => 'Indeks', 'keterangan' => 'Indeks', 'is_active' => true, 'created_by' => $username, 'created_at' => $now, 'updated_at' => $now],
            ['id' => Str::uuid(), 'satuan' => 'Poin', 'keterangan' => 'Skor/Poin', 'is_active' => true, 'created_by' => $username, 'created_at' => $now, 'updated_at' => $now],
            ['id' => Str::uuid(), 'satuan' => 'Rupiah', 'keterangan' => 'Mata Uang', 'is_active' => true, 'created_by' => $username, 'created_at' => $now, 'updated_at' => $now],
        ];

        DB::table('master_satuan')->insert($satuan);
    }
}
