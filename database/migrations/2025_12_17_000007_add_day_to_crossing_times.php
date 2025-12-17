<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('crossing_times', function (Blueprint $table) {
            $table->enum('day', ['thursday', 'friday', 'saturday'])->nullable()->after('race_class_id');
        });
    }

    public function down(): void
    {
        Schema::table('crossing_times', function (Blueprint $table) {
            $table->dropColumn('day');
        });
    }
};

