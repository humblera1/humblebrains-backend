<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Property extends Model
{
    use HasFactory, HasTranslations;

    public $translatable = ['label', 'description'];

    public function games()
    {
        return $this->belongsToMany(Game::class, 'game_property')
            ->withPivot('level', 'value')
            ->withTimestamps();
    }
}
