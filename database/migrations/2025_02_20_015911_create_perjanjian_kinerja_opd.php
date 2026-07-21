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
        Schema::create('pk_opd', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->foreignUuid('sasaran_opd_id');
            $table->foreign('sasaran_opd_id')->references('id')->on('sasaran_opd');

            $table->foreignUuid('indikator_opd_id');
            $table->foreign('indikator_opd_id')->references('id')->on('indikator_opd');

            $table->foreignUuid('master_opd_id');
            $table->foreign('master_opd_id')->references('id')->on('master_opd');

            $table->text('eselon')->nullable();
           
            $table->year('tahun');
            $table->text('target')->nullable();

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
        Schema::dropIfExists('perjanjian_kinerja_opd');
    }
};
