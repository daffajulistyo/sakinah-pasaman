<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('master_program', function (Blueprint $table) {
            $table->uuid('master_opd_id')->nullable()->after('kode_skpd');
            $table->foreign('master_opd_id')->references('id')->on('master_opd')->onDelete('set null');
        });

        DB::statement('UPDATE master_program SET master_opd_id = (SELECT id FROM master_opd WHERE master_opd.kode_opd = master_program.kode_skpd) WHERE kode_skpd IS NOT NULL');
    }

    public function down(): void
    {
        Schema::table('master_program', function (Blueprint $table) {
            $table->dropForeign(['master_opd_id']);
            $table->dropColumn('master_opd_id');
        });
    }
};
