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
        Schema::create('opd_pendukung_indikator', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->foreignUuid('pohon_kinerja_sasaran_id')->nullable();
            $table->foreign('pohon_kinerja_sasaran_id')->references('id')->on('pohon_kinerja_sasaran');

            $table->foreignUuid('pohon_kinerja_indikator_id')->nullable();
            $table->foreign('pohon_kinerja_indikator_id')->references('id')->on('pohon_kinerja_indikator');

            $table->foreignUuid('master_opd_id')->nullable();
            $table->foreign('master_opd_id')->references('id')->on('master_opd');

            $table->boolean('is_active')->default(false);
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
        Schema::dropIfExists('opd_pendukung_indikator');
    }
};
