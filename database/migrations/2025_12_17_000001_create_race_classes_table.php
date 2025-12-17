<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('race_classes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        // Add class_id to entries
        Schema::table('entries', function (Blueprint $table) {
            $table->foreignId('race_class_id')->nullable()->constrained()->nullOnDelete();
        });

        // Add class_id to race_sessions
        Schema::table('race_sessions', function (Blueprint $table) {
            $table->foreignId('race_class_id')->nullable()->constrained()->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('race_sessions', function (Blueprint $table) {
            $table->dropConstrainedForeignId('race_class_id');
        });
        Schema::table('entries', function (Blueprint $table) {
            $table->dropConstrainedForeignId('race_class_id');
        });
        Schema::dropIfExists('race_classes');
    }
};

