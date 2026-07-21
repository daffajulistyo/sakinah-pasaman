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
        Schema::create('cascading', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('pohon_kinerja_sasaran_id')->nullable();
            $table->foreign('pohon_kinerja_sasaran_id')->references('id')->on('pohon_kinerja_sasaran');
 
            $table->unsignedInteger('order')->default(0);
            $table->string('id_program', 100);
            $table->string('kode_program', 100);
            $table->text('nama_program')->nullable();
            $table->unsignedInteger('id_skpd')->default(0);
            $table->year('tahun')->default(0);
            $table->text('pagu')->nullable();
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
        Schema::dropIfExists('cascading');
    }
};
