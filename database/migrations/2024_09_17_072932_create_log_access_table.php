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
        Schema::create('log_access', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('user',100);
            $table->string('ip_address',100);
            $table->text('user_agent');
            $table->string('unix_time',100);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('log_access');
    }
};
