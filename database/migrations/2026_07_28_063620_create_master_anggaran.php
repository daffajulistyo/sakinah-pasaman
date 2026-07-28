<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('master_anggaran', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('kode_anggaran', 50);
            $table->string('nama_anggaran', 255);
            $table->uuid('master_opd_id')->nullable();
            $table->foreign('master_opd_id')->references('id')->on('master_opd')->onDelete('set null');
            $table->uuid('master_program_id')->nullable();
            $table->foreign('master_program_id')->references('id')->on('master_program')->onDelete('set null');
            $table->decimal('jumlah', 15, 2)->default(0);
            $table->year('tahun')->nullable();
            $table->boolean('is_active')->default(true);
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('master_anggaran');
    }
};
