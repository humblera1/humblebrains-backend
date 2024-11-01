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
        Schema::create('session_games', function (Blueprint $table) {
            $table->id();
            $table->foreignId('session_id')->constrained('program_sessions');
            $table->foreignId('game_id')->nullable()->constrained();
            $table->foreignId('played_game_id')->nullable()->constrained('history');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('session_games');
    }
};
