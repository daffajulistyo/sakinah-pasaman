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
        Schema::create('atasan_pegawai', function (Blueprint $table) {
             $table->uuid('id')->primary();

            $table->string('nip_pegawai', 100);          
            $table->string('nip_atasan', 100); 
            $table->string('nama_atasan', 200);          
            $table->string('jabatan_atasan', 255);          
            $table->string('unit_kerja_atasan', 255);          

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
        Schema::dropIfExists('atasan_pegawai');
    }
};
