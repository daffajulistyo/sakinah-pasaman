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
        Schema::create('rencana_aksi', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->foreignUuid('pohon_kinerja_sasaran_id');
            $table->foreign('pohon_kinerja_sasaran_id')->references('id')->on('pohon_kinerja_sasaran');

            $table->foreignUuid('pohon_kinerja_indikator_id');
            $table->foreign('pohon_kinerja_indikator_id')->references('id')->on('pohon_kinerja_indikator');

            $table->text('target_tw1')->nullable();
            $table->text('target_tw2')->nullable();
            $table->text('target_tw3')->nullable();
            $table->text('target_tw4')->nullable();

            $table->text('realisasi_tw1')->nullable();
            $table->text('realisasi_tw2')->nullable();
            $table->text('realisasi_tw3')->nullable();
            $table->text('realisasi_tw4')->nullable();

            $table->text('capaian_tw1')->nullable();
            $table->text('capaian_tw2')->nullable();
            $table->text('capaian_tw3')->nullable();
            $table->text('capaian_tw4')->nullable();

            $table->year('tahun');
            $table->boolean('murni')->default(false);
            $table->boolean('is_active')->default(true);            
            $table->string('created_by', 100)->nullable();
            $table->string('updated_by', 100)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rencana_aksi');
    }
};
