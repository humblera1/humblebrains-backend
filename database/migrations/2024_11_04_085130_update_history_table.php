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
        Schema::table('history', function (Blueprint $table) {
            $table->tinyInteger('start_from_level');
            $table->integer('game_sequence_number')->comment('Sequential number of the game for the user');

            $table->unique(['user_id', 'game_id', 'game_sequence_number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('history', function (Blueprint $table) {
            $table->dropUnique(['user_id', 'game_id', 'game_sequence_number']);

            $table->dropColumn(['start_from_level', 'game_sequence_number']);
        });
    }
};
