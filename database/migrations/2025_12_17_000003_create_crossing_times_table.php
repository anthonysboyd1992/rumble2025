<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('crossing_times', function (Blueprint $table) {
            $table->id();
            $table->foreignId('race_class_id')->constrained()->onDelete('cascade');
            $table->string('session_name');
            $table->integer('index')->nullable();
            $table->string('car_number');
            $table->string('driver_name');
            $table->string('trns_id')->nullable();
            $table->string('laptime');
            $table->string('speed')->nullable();
            $table->string('hits_power')->nullable();
            $table->string('misc')->nullable();
            $table->timestamps();

            $table->index(['race_class_id', 'car_number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('crossing_times');
    }
};

