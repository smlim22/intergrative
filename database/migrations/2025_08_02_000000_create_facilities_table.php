<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('facilities', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('category');
            $table->text('description')->nullable();
            $table->decimal('hourly_rate', 8, 2)->nullable();
            $table->decimal('half_day_rate', 8, 2)->nullable();
            $table->decimal('full_day_rate', 8, 2)->nullable();
            $table->integer('capacity')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('facilities');
    }
};
