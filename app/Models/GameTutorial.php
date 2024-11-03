<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Translatable\HasTranslations;

class GameTutorial extends Model
{
    use HasFactory, HasTranslations;

    protected $fillable = ['content'];

    public $translatable = ['content'];

    public function game(): BelongsTo
    {
        return $this->belongsTo(Game::class);
    }
}
