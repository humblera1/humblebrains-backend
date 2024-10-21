<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    const CATEGORY_MEMORY = 'memory';
    const CATEGORY_LOGIC = 'logic';
    const CATEGORY_ATTENTION = 'attention';

    const AVAILABLE_CATEGORIES_LIST = [
        self::CATEGORY_MEMORY,
        self::CATEGORY_LOGIC,
        self::CATEGORY_ATTENTION,
    ];

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->enum('name', self::AVAILABLE_CATEGORIES_LIST);
            $table->json('label');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
