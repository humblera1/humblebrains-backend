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
        Schema::table('game_level_properties', function (Blueprint $table) {
            $table->dropConstrainedForeignId('level_id');

            $table->tinyInteger('level');
            $table->unique(['game_id', 'level', 'property_id'], 'unique_game_level_property');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {

        Schema::table('game_level_properties', function (Blueprint $table) {
            $table->dropUnique('unique_game_level_property');
            $table->dropColumn('level');

            $table->foreignId('level_id')->constrained()->onDelete('cascade');
            $table->unique(['game_id', 'level_id', 'property_id'], 'unique_game_level_property');
        });


    }
};
