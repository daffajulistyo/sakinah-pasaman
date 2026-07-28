<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('master_kegiatan', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('kode_kegiatan', 50);
            $table->string('nama_kegiatan', 255);
            $table->uuid('master_program_id');
            $table->foreign('master_program_id')->references('id')->on('master_program')->onDelete('cascade');
            $table->boolean('is_active')->default(true);
            $table->string('created_by', 100)->nullable();
            $table->string('updated_by', 100)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('master_kegiatan');
    }
};
