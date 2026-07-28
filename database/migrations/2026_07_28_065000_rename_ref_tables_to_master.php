<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $renames = [
            'ref_eselon' => 'master_eselon',
            'ref_golongan' => 'master_golongan',
            'ref_jenis_jabatan' => 'master_jenis_jabatan',
            'ref_jabatan' => 'master_jabatan',
            'ref_sub_opd' => 'master_sub_opd',
        ];

        foreach ($renames as $from => $to) {
            if (Schema::hasTable($from) && !Schema::hasTable($to)) {
                DB::statement("RENAME TABLE `{$from}` TO `{$to}`");
            }
        }
    }

    public function down(): void
    {
        $renames = [
            'master_eselon' => 'ref_eselon',
            'master_golongan' => 'ref_golongan',
            'master_jenis_jabatan' => 'ref_jenis_jabatan',
            'master_jabatan' => 'ref_jabatan',
            'master_sub_opd' => 'ref_sub_opd',
        ];

        foreach ($renames as $from => $to) {
            if (Schema::hasTable($from) && !Schema::hasTable($to)) {
                DB::statement("RENAME TABLE `{$from}` TO `{$to}`");
            }
        }
    }
};
