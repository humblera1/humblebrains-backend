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
        Schema::table('session_games', function (Blueprint $table) {
            $table->dropForeign(['session_id']);
            $table->renameColumn('session_id', 'program_session_id');
            $table->foreign('program_session_id')->references('id')->on('program_sessions')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('session_games', function (Blueprint $table) {
            $table->dropForeign(['program_session_id']);
            $table->renameColumn('program_session_id', 'session_id');
            $table->foreign('session_id')->references('id')->on('program_sessions')->onDelete('cascade');
        });
    }
};
