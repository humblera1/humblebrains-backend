<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Spatie\Translatable\HasTranslations;

class Game extends Model
{
    use HasFactory, HasTranslations;

    public $translatable = ['label', 'description'];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function properties(): BelongsToMany
    {
        return $this->belongsToMany(Property::class, 'game_level_properties')
            ->withPivot('level', 'value');
    }

    public function tutorial(): HasOne
    {
        return $this->hasOne(GameTutorial::class);
    }

    public function propertiesByLevel(): Collection
    {
        return $this->properties()
            ->select(['level', 'type', 'name', 'value'])
            ->get()
            ->groupBy('pivot.level')
            ->map(function ($group) {
                return $group->pluckWithCast('type', 'value', 'name');
            });
    }
}
