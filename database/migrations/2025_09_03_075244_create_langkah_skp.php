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
        Schema::create('skp_langkah', function (Blueprint $table) {
            $table->uuid('id')->primary();
             
            $table->foreignUuid('skp_id');
            $table->foreign('skp_id')->references('id')->on('skp_periode');

            $table->foreignUuid('indikator_skp_id');
            $table->foreign('indikator_skp_id')->references('id')->on('skp_indikator');

            $table->text('langkah');
            $table->text('target_tw1');
            $table->text('target_tw2');
            $table->text('target_tw3');
            $table->text('target_tw4');
            $table->text('satuan')->nullable();
            $table->text('keterangan')->nullable();
            
            $table->text('realisasi_tw1')->nullable();
            $table->text('realisasi_tw2')->nullable();
            $table->text('realisasi_tw3')->nullable();
            $table->text('realisasi_tw4')->nullable();

            $table->text('capaian_tw1')->nullable();
            $table->text('capaian_tw2')->nullable();
            $table->text('capaian_tw3')->nullable();
            $table->text('capaian_tw4')->nullable();
 
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
        Schema::dropIfExists('skp_langkah');
    }
};
