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
        Schema::create('rkpd', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('pohon_kinerja_sasaran_id');
            $table->foreign('pohon_kinerja_sasaran_id')->references('id')->on('pohon_kinerja_sasaran');


            $table->foreignUuid('pohon_kinerja_indikator_id');
            $table->foreign('pohon_kinerja_indikator_id')->references('id')->on('pohon_kinerja_indikator');

            $table->year('tahun');
            $table->text('target')->nullable();
            $table->boolean('murni')->default(false);

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rkpd');
    }
};
