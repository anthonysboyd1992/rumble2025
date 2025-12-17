<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('qualifying_times', function (Blueprint $table) {
            $table->id();
            $table->foreignId('race_class_id')->constrained()->onDelete('cascade');
            $table->string('session_name'); // e.g., "Qualifying 1", "Qualifying 2"
            $table->string('car_number');
            $table->string('driver_name');
            $table->string('tx_id')->nullable();
            $table->integer('place')->nullable();
            $table->integer('laps')->nullable();
            $table->string('adjust')->nullable();
            $table->string('last_time')->nullable();
            $table->string('fast_time'); // main sorting field
            $table->integer('fast_lap')->nullable();
            $table->string('misc')->nullable();
            $table->timestamps();

            $table->index(['race_class_id', 'car_number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('qualifying_times');
    }
};

