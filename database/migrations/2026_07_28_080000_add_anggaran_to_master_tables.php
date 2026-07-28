<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('master_program', function (Blueprint $table) {
            $table->decimal('anggaran', 15, 2)->default(0)->after('tahun');
        });
        Schema::table('master_kegiatan', function (Blueprint $table) {
            $table->decimal('anggaran', 15, 2)->default(0)->after('master_program_id');
        });
        Schema::table('master_sub_kegiatan', function (Blueprint $table) {
            $table->decimal('anggaran', 15, 2)->default(0)->after('master_kegiatan_id');
        });
    }

    public function down(): void
    {
        Schema::table('master_sub_kegiatan', function (Blueprint $table) {
            $table->dropColumn('anggaran');
        });
        Schema::table('master_kegiatan', function (Blueprint $table) {
            $table->dropColumn('anggaran');
        });
        Schema::table('master_program', function (Blueprint $table) {
            $table->dropColumn('anggaran');
        });
    }
};
