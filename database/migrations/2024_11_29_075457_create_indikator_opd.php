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
        Schema::create('indikator_opd', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('tujuan_opd_id')->nullable();
            $table->foreign('tujuan_opd_id')->references('id')->on('tujuan_opd');

            $table->foreignUuid('sasaran_opd_id')->nullable();
            $table->foreign('sasaran_opd_id')->references('id')->on('sasaran_opd');  
            
            $table->foreignUuid('master_opd_id')->nullable();
            $table->foreign('master_opd_id')->references('id')->on('master_opd');   
            
            $table->unsignedInteger('order')->default(0);
            $table->string('indikator', 255);
            $table->text('defenisi')->nullable();
            $table->text('kegunaan')->nullable();
            $table->text('rilis')->nullable();
            $table->text('sumber_data')->nullable();
            $table->text('formula_perhitungan')->nullable();

            $table->foreignUuid('satuan_id')->nullable();
            $table->foreign('satuan_id')->references('id')->on('master_satuan');    
            
            $table->text('baseline')->nullable();
            $table->text('target_1')->nullable();
            $table->text('target_2')->nullable();
            $table->text('target_3')->nullable();
            $table->text('target_4')->nullable();
            $table->text('target_5')->nullable();
            $table->text('target_6')->nullable();
            $table->boolean('is_active')->default(false);
            $table->boolean('is_indikator_kinerja_utama')->default(true);
            $table->boolean('is_tujuan')->default(false);
            
            $table->foreignUuid('pohon_kinerja_visi_id');
            $table->foreign('pohon_kinerja_visi_id')->references('id')->on('pohon_kinerja_visi');

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
        Schema::dropIfExists('indikator_opd');
    }
};
