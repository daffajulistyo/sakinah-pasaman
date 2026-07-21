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
        Schema::create('skp_indikator', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->foreignUuid('skp_id');
            $table->foreign('skp_id')->references('id')->on('skp_periode');

            $table->foreignUuid('sasaran_opd_id');
            $table->foreign('sasaran_opd_id')->references('id')->on('sasaran_opd');

            $table->foreignUuid('indikator_opd_id');
            $table->foreign('indikator_opd_id')->references('id')->on('indikator_opd');
            
            $table->text('target');
            $table->text('satuan')->nullable();

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
        Schema::dropIfExists('skp_indikator');
    }
};
