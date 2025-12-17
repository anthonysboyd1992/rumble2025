<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('race_sessions', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g., "Qualifying 1", "Heat 1", "A Feature 1"
            $table->enum('type', ['qualifying', 'heat', 'bmain', 'amain', 'dash']);
            $table->enum('day', ['thursday', 'friday', 'saturday']);
            $table->enum('group', ['all', 'odd', 'even'])->default('all');
            $table->integer('laps')->nullable();
            $table->string('duration')->nullable(); // e.g., "00:05:32.000"
            $table->string('sponsor')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('race_sessions');
    }
};

