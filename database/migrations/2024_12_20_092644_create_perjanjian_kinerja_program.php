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
        Schema::create('perjanjian_kinerja_program', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->foreignUuid('pohon_kinerja_sasaran_id');
            $table->foreign('pohon_kinerja_sasaran_id')->references('id')->on('pohon_kinerja_sasaran');

            $table->year('tahun');
            $table->json('list_program');
            $table->integer('anggaran');
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
        Schema::dropIfExists('perjanjian_kinerja_program');
    }
};
