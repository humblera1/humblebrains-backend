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
            $table->smallInteger('started_from_level');
            $table->smallInteger('correct_answers_amount');
            $table->boolean('within_session');
            $table->boolean('is_target_completed');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('history', function (Blueprint $table) {
            $table->dropColumn(['started_from_level', 'correct_answers_amount', 'within_session', 'is_target_completed']);
        });
    }
};
