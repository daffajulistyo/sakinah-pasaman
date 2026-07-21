<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('indikator_opd', function (Blueprint $table) {
            $table->text('realisasi_1')->nullable()->after('target_6');
            $table->text('realisasi_2')->nullable()->after('realisasi_1');
            $table->text('realisasi_3')->nullable()->after('realisasi_2');
            $table->text('realisasi_4')->nullable()->after('realisasi_3');
            $table->text('realisasi_5')->nullable()->after('realisasi_4');
            $table->text('realisasi_6')->nullable()->after('realisasi_5');

            $table->text('capaian_1')->nullable()->after('realisasi_6');
            $table->text('capaian_2')->nullable()->after('capaian_1');
            $table->text('capaian_3')->nullable()->after('capaian_2');
            $table->text('capaian_4')->nullable()->after('capaian_3');
            $table->text('capaian_5')->nullable()->after('capaian_4');
            $table->text('capaian_6')->nullable()->after('capaian_5');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('indikator_opd', function (Blueprint $table) {
            $table->dropColumn('realisasi_1');
            $table->dropColumn('realisasi_2');
            $table->dropColumn('realisasi_3');
            $table->dropColumn('realisasi_4');
            $table->dropColumn('realisasi_5');
            $table->dropColumn('realisasi_6');
            $table->dropColumn('capaian_1');
            $table->dropColumn('capaian_2');
            $table->dropColumn('capaian_3');
            $table->dropColumn('capaian_4');
            $table->dropColumn('capaian_5');
            $table->dropColumn('capaian_6');
        });
    }
};
