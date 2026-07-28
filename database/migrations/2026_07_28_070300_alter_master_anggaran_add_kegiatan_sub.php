<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('master_anggaran', function (Blueprint $table) {
            $table->dropForeign(['master_opd_id']);
            $table->dropColumn('master_opd_id');

            $table->uuid('master_kegiatan_id')->nullable()->after('master_program_id');
            $table->foreign('master_kegiatan_id')->references('id')->on('master_kegiatan')->onDelete('set null');

            $table->uuid('master_sub_kegiatan_id')->nullable()->after('master_kegiatan_id');
            $table->foreign('master_sub_kegiatan_id')->references('id')->on('master_sub_kegiatan')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('master_anggaran', function (Blueprint $table) {
            $table->dropForeign(['master_sub_kegiatan_id']);
            $table->dropColumn('master_sub_kegiatan_id');
            $table->dropForeign(['master_kegiatan_id']);
            $table->dropColumn('master_kegiatan_id');

            $table->uuid('master_opd_id')->nullable()->after('id');
            $table->foreign('master_opd_id')->references('id')->on('master_opd')->onDelete('set null');
        });
    }
};
