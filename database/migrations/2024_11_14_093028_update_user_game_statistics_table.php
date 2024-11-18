<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('user_game_statistics', function (Blueprint $table) {
            $table->renameColumn('total_score', 'max_score');
            $table->smallInteger('mean_correct_answers_amount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_game_statistics', function (Blueprint $table) {
            $table->renameColumn('max_score', 'total_score');
            $table->dropColumn('mean_correct_answers_amount');
        });
    }
};
