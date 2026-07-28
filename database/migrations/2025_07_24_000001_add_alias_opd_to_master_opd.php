<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('master_opd', function (Blueprint $table) {
            if (!Schema::hasColumn('master_opd', 'alias_opd')) {
                $table->string('alias_opd', 100)->nullable()->after('nama_opd');
            }
        });
    }

    public function down(): void
    {
        Schema::table('master_opd', function (Blueprint $table) {
            if (Schema::hasColumn('master_opd', 'alias_opd')) {
                $table->dropColumn('alias_opd');
            }
        });
    }
};
