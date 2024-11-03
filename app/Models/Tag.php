<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Spatie\Translatable\HasTranslations;

class Tag extends Model
{
    use HasFactory, HasTranslations;

    protected $fillable = ['name', 'label'];

    public $translatable = ['label'];

    public function taggables(): MorphToMany
    {
        return $this->morphToMany(Game::class, 'taggable');
    }
}
