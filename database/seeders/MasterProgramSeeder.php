<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class MasterProgramSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        // ponytail: seed OPD pendukung for all indikator (needed for cascading add)
        $indikators = DB::table('pohon_kinerja_indikator')->select('id', 'pohon_kinerja_sasaran_id')->get();
        $firstOpd = DB::table('master_opd')->select('id')->first();
        if ($firstOpd && DB::table('opd_pendukung_indikator')->count() === 0) {
            foreach ($indikators as $ind) {
                DB::table('opd_pendukung_indikator')->insert([
                    'id'                          => Str::uuid(),
                    'pohon_kinerja_indikator_id'  => $ind->id,
                    'pohon_kinerja_sasaran_id'    => $ind->pohon_kinerja_sasaran_id,
                    'master_opd_id'               => $firstOpd->id,
                    'is_active'                   => true,
                    'created_by'                  => 'system',
                    'created_at'                  => $now,
                    'updated_at'                  => $now,
                ]);
            }
        }

        // ponytail: seed 5 programs for each of first 5 OPDs
        $programs = [
            ['PROG-001', 'Program Penunjang Urusan Pemerintahan Daerah'],
            ['PROG-002', 'Program Peningkatan Pelayanan Publik'],
            ['PROG-003', 'Program Pengembangan Infrastruktur'],
            ['PROG-004', 'Program Pemberdayaan Masyarakat'],
            ['PROG-005', 'Program Peningkatan Kualitas Pendidikan'],
        ];

        $opds = DB::table('master_opd')->select('kode_opd', 'nama_opd')->limit(5)->get();

        foreach ($opds as $opd) {
            foreach ($programs as $prog) {
                $exists = DB::table('master_program')
                    ->where('kode_program', $prog[0])
                    ->where('kode_skpd', $opd->kode_opd)
                    ->exists();
                if ($exists) continue;

                DB::table('master_program')->insert([
                    'id'            => Str::uuid(),
                    'kode_program'  => $prog[0],
                    'nama_program'  => $prog[1] . ' - ' . $opd->nama_opd,
                    'kode_skpd'     => $opd->kode_opd,
                    'tahun'         => '2025',
                    'is_active'     => true,
                    'created_by'    => 'system',
                    'created_at'    => $now,
                    'updated_at'    => $now,
                ]);
            }
        }

        // ponytail: add menu entry for sidebar "Master Program" under "Data" group
        $menuExists = DB::table('menus')
            ->where('route', '/data/masterprogram')
            ->exists();
        if (!$menuExists) {
            DB::table('menus')->insert([
                'id'             => Str::uuid(),
                'menugroup_id'   => '0acdc906-d7f9-43e4-ba50-b767477c5b16',
                'menu_label'     => 'Master Program',
                'menu_icon'      => '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"/></svg>',
                'menu_desc'      => 'Link menuju halaman Master Program Anggaran',
                'menu_order'     => 99,
                'route'          => '/data/masterprogram',
                'action_id'      => '36e55eda-ffc4-47d4-89ec-e589e1abcb9c', // ponytail: reuse existing action
                'type'           => 'core',
                'created_by'     => 'system',
                'created_at'     => $now,
                'updated_at'     => $now,
            ]);
        }
    }
}
