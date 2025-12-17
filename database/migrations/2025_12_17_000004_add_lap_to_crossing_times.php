<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('crossing_times', function (Blueprint $table) {
            $table->integer('lap')->nullable()->after('trns_id');
        });
    }

    public function down(): void
    {
        Schema::table('crossing_times', function (Blueprint $table) {
            $table->dropColumn('lap');
        });
    }
};

