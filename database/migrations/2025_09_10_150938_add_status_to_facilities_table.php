<?php
// filepath: database/migrations/xxxx_add_status_to_facilities_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('facilities', function (Blueprint $table) {
            if (!Schema::hasColumn('facilities', 'status')) {
                $table->enum('status', ['active', 'disabled'])->default('active')->after('capacity');
            }
        });
    }

    public function down(): void
    {
        Schema::table('facilities', function (Blueprint $table) {
            if (Schema::hasColumn('facilities', 'status')) {
                $table->dropColumn('status');
            }
        });
    }
};