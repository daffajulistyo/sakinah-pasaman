<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class RefSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('master_eselon')->insert([
            ['id' => Str::uuid(), 'kode' => '11', 'nama' => 'I.A', 'level' => 1, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['id' => Str::uuid(), 'kode' => '12', 'nama' => 'I.B', 'level' => 1, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['id' => Str::uuid(), 'kode' => '21', 'nama' => 'II.A', 'level' => 2, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['id' => Str::uuid(), 'kode' => '22', 'nama' => 'II.B', 'level' => 2, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['id' => Str::uuid(), 'kode' => '31', 'nama' => 'III.A', 'level' => 3, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['id' => Str::uuid(), 'kode' => '32', 'nama' => 'III.B', 'level' => 3, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['id' => Str::uuid(), 'kode' => '41', 'nama' => 'IV.A', 'level' => 4, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['id' => Str::uuid(), 'kode' => '42', 'nama' => 'IV.B', 'level' => 4, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['id' => Str::uuid(), 'kode' => '99', 'nama' => 'Non Eselon', 'level' => 99, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
        ]);

        DB::table('master_golongan')->insert([
            ['id' => Str::uuid(), 'kode' => '41', 'golongan' => 'IV/a', 'pangkat' => 'Pembina', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['id' => Str::uuid(), 'kode' => '42', 'golongan' => 'IV/b', 'pangkat' => 'Pembina TK I', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['id' => Str::uuid(), 'kode' => '43', 'golongan' => 'IV/c', 'pangkat' => 'Pembina Utama Muda', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['id' => Str::uuid(), 'kode' => '31', 'golongan' => 'III/a', 'pangkat' => 'Penata Muda', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['id' => Str::uuid(), 'kode' => '32', 'golongan' => 'III/b', 'pangkat' => 'Penata Muda TK I', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['id' => Str::uuid(), 'kode' => '33', 'golongan' => 'III/c', 'pangkat' => 'Penata', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['id' => Str::uuid(), 'kode' => '34', 'golongan' => 'III/d', 'pangkat' => 'Penata TK I', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['id' => Str::uuid(), 'kode' => '21', 'golongan' => 'II/a', 'pangkat' => 'Pengatur Muda', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['id' => Str::uuid(), 'kode' => '22', 'golongan' => 'II/b', 'pangkat' => 'Pengatur Muda TK I', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['id' => Str::uuid(), 'kode' => '23', 'golongan' => 'II/c', 'pangkat' => 'Pengatur', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
        ]);

        $jabatanStrukturalId = Str::uuid();
        $jabatanFungsionalId = Str::uuid();
        $jabatanPelaksanaId = Str::uuid();

        DB::table('master_jenis_jabatan')->insert([
            ['id' => $jabatanStrukturalId, 'kode' => '1', 'nama' => 'Struktural', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['id' => $jabatanFungsionalId, 'kode' => '2', 'nama' => 'Fungsional', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['id' => $jabatanPelaksanaId, 'kode' => '3', 'nama' => 'Pelaksana', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
        ]);

        DB::table('master_jabatan')->insert([
            ['id' => Str::uuid(), 'kode' => 'STR-001', 'nama' => 'Kepala Dinas', 'ref_jenis_jabatan_id' => $jabatanStrukturalId, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['id' => Str::uuid(), 'kode' => 'STR-002', 'nama' => 'Sekretaris', 'ref_jenis_jabatan_id' => $jabatanStrukturalId, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['id' => Str::uuid(), 'kode' => 'STR-003', 'nama' => 'Kepala Bidang', 'ref_jenis_jabatan_id' => $jabatanStrukturalId, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['id' => Str::uuid(), 'kode' => 'STR-004', 'nama' => 'Kepala Seksi', 'ref_jenis_jabatan_id' => $jabatanStrukturalId, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['id' => Str::uuid(), 'kode' => 'STR-005', 'nama' => 'Kepala Sub Bagian', 'ref_jenis_jabatan_id' => $jabatanStrukturalId, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['id' => Str::uuid(), 'kode' => 'FNG-001', 'nama' => 'Analis Kepegawaian', 'ref_jenis_jabatan_id' => $jabatanFungsionalId, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['id' => Str::uuid(), 'kode' => 'FNG-002', 'nama' => 'Pranata Komputer', 'ref_jenis_jabatan_id' => $jabatanFungsionalId, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['id' => Str::uuid(), 'kode' => 'FNG-003', 'nama' => 'Perencana', 'ref_jenis_jabatan_id' => $jabatanFungsionalId, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['id' => Str::uuid(), 'kode' => 'PLS-001', 'nama' => 'Pengadministrasi Umum', 'ref_jenis_jabatan_id' => $jabatanPelaksanaId, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['id' => Str::uuid(), 'kode' => 'PLS-002', 'nama' => 'Pengelola Data', 'ref_jenis_jabatan_id' => $jabatanPelaksanaId, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
        ]);

        $masterOpd = DB::table('master_opd')->first();

        if ($masterOpd) {
            DB::table('master_sub_opd')->insert([
                ['id' => Str::uuid(), 'kode' => 'SUB-001', 'nama' => 'Sub Bagian Umum dan Kepegawaian', 'master_opd_id' => $masterOpd->id, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
                ['id' => Str::uuid(), 'kode' => 'SUB-002', 'nama' => 'Sub Bagian Perencanaan dan Keuangan', 'master_opd_id' => $masterOpd->id, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
                ['id' => Str::uuid(), 'kode' => 'SUB-003', 'nama' => 'Seksi Pengembangan Aplikasi', 'master_opd_id' => $masterOpd->id, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
                ['id' => Str::uuid(), 'kode' => 'SUB-004', 'nama' => 'Seksi Infrastruktur dan Keamanan Informasi', 'master_opd_id' => $masterOpd->id, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
                ['id' => Str::uuid(), 'kode' => 'SUB-005', 'nama' => 'Seksi Data dan Statistik', 'master_opd_id' => $masterOpd->id, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ]);
        }
    }
}
