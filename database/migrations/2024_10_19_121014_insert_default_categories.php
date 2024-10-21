<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::table('categories')->insert([
            [
                'name' => 'logic',
                'label' => json_encode(
                    [
                        'ru' => 'Логика',
                        'en' => 'Logic',
                    ],
                    JSON_UNESCAPED_UNICODE
                ),
            ],
            [
                'name' => 'memory',
                'label' => json_encode(
                    [
                        'ru' => 'Память',
                        'en' => 'Memory',
                    ],
                    JSON_UNESCAPED_UNICODE
                ),
            ],
            [
                'name' => 'attention',
                'label' => json_encode(
                    [
                        'ru' => 'Внимание',
                        'en' => 'Attention',
                    ],
                    JSON_UNESCAPED_UNICODE
                ),
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('categories')->whereIn('name', ['logic', 'memory', 'attention'])->delete();
    }
};
