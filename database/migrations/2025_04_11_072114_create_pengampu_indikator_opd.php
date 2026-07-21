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
        Schema::create('pengampu_indikator_opd', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->foreignUuid('sasaran_opd_id');
            $table->foreign('sasaran_opd_id')->references('id')->on('sasaran_opd');

            $table->foreignUuid('indikator_opd_id');
            $table->foreign('indikator_opd_id')->references('id')->on('indikator_opd');

            $table->foreignUuid('master_opd_id');
            $table->foreign('master_opd_id')->references('id')->on('master_opd');

            $table->string('nip', 100);
            $table->string('nama', 200);
            $table->string('jns_jbtn_id', 100);
            $table->string('jns_jbtn_nm', 200);
            $table->string('jabatan_id', 100);
            $table->string('jabatan_nm', 200);
            $table->string('eselon_id', 100);
            $table->string('eselon_nm', 200);

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
        Schema::dropIfExists('pengampu_indikator_opd');
    }
};
