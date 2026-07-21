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
        Schema::create('master_opd', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('kode_opd', 255);
            $table->integer('simpeg_opd_id')->nullable();
            $table->text('opd_unit_id')->nullable();
            $table->text('opd_unit')->nullable();
            $table->text('nama_opd')->nullable();
            $table->string('alamat', 255)->nullable();
            $table->string('telp', 255)->nullable();
            $table->string('website', 255)->nullable();
            $table->string('email', 255)->nullable();
            $table->integer('ikd_opd_id')->nullable();
            $table->unsignedInteger('order')->default(0);
            $table->unsignedInteger('parent_id')->default(0);
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
        Schema::dropIfExists('master_opd');
    }
};
