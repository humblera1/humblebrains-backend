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
            $table->renameColumn('start_from_level', 'max_unlocked_level');
            $table->decimal('mean_reaction_time');
            $table->decimal('accuracy', 3, 1);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('history', function (Blueprint $table) {
            $table->renameColumn('max_unlocked_level', 'start_from_level');
            $table->dropColumn('mean_reaction_time');
            $table->dropColumn('accuracy');
        });
    }
};
