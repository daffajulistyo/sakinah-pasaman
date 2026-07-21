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
        Schema::create('pohon_kinerja_visi', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->unsignedInteger('period_starts')->default(0);
            $table->unsignedInteger('period_ends')->default(0);
            $table->text('visi');
            $table->boolean('is_active')->default(false);
            $table->string('created_by', 100)->nullable();
            $table->string('updated_by', 100)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pohon_kinerja_visi');
    }
};
