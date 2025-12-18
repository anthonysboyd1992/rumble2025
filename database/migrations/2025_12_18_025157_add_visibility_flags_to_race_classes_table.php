<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('race_classes', function (Blueprint $table) {
            $table->boolean('show_on_leaderboard')->default(true);
            $table->boolean('show_on_practice')->default(true);
        });
    }

    public function down(): void
    {
        Schema::table('race_classes', function (Blueprint $table) {
            $table->dropColumn(['show_on_leaderboard', 'show_on_practice']);
        });
    }
};
