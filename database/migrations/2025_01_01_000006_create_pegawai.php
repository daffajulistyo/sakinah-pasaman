<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pegawai', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')->nullable()->constrained('users');
            $table->string('nip', 50)->unique();
            $table->string('nama', 200);
            $table->string('gelar_depan', 50)->nullable();
            $table->string('gelar_belakang', 50)->nullable();
            $table->string('tempat_lahir', 100)->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->enum('jenis_kelamin', ['L', 'P'])->nullable();
            $table->text('alamat')->nullable();
            $table->string('no_hp', 20)->nullable();
            $table->string('email', 100)->nullable();
            $table->foreignUuid('master_opd_id')->nullable()->constrained('master_opd');
            $table->string('sub_opd_id', 100)->nullable();
            $table->string('sub_opd_nm', 200)->nullable();
            $table->foreignUuid('ref_eselon_id')->nullable()->constrained('ref_eselon');
            $table->foreignUuid('ref_golongan_id')->nullable()->constrained('ref_golongan');
            $table->foreignUuid('ref_jenis_jabatan_id')->nullable()->constrained('ref_jenis_jabatan');
            $table->foreignUuid('ref_jabatan_id')->nullable()->constrained('ref_jabatan');
            $table->string('jenjang', 50)->nullable();
            $table->string('foto', 255)->nullable();
            $table->boolean('is_active')->default(true);
            $table->string('created_by', 100)->nullable();
            $table->string('updated_by', 100)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pegawai');
    }
};
