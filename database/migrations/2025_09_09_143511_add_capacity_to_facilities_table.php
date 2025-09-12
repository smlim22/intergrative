<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('facilities', function (Blueprint $table) {
        
            if (!Schema::hasColumn('facilities', 'capacity')) {
                $table->integer('capacity')->nullable()->after('description');
            }
        });
    }

    public function down(): void
    {
        Schema::table('facilities', function (Blueprint $table) {
            if (Schema::hasColumn('facilities', 'capacity')) {
                $table->dropColumn('capacity');
            }
        });
    }
};