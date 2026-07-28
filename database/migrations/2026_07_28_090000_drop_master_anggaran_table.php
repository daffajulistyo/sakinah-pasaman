<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('master_anggaran');
    }

    public function down(): void
    {
        Schema::create('master_anggaran', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('kode_anggaran', 50);
            $table->string('nama_anggaran', 255);
            $table->uuid('master_program_id')->nullable();
            $table->uuid('master_kegiatan_id')->nullable();
            $table->uuid('master_sub_kegiatan_id')->nullable();
            $table->decimal('jumlah', 15, 2)->default(0);
            $table->year('tahun')->nullable();
            $table->boolean('is_active')->default(true);
            $table->string('created_by', 100)->nullable();
            $table->timestamps();
        });
    }
};
