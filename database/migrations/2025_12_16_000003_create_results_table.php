<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('entry_id')->constrained()->onDelete('cascade');
            $table->foreignId('race_session_id')->constrained()->onDelete('cascade');
            $table->integer('position');
            $table->string('time')->nullable(); // qualifying time
            $table->integer('starting_position')->nullable();
            $table->integer('points_earned')->default(0);
            $table->boolean('is_dns')->default(false);
            $table->boolean('is_dnf')->default(false);
            $table->timestamps();

            $table->unique(['entry_id', 'race_session_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('results');
    }
};

