<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Spatie\Translatable\HasTranslations;

class Game extends Model
{
    use HasFactory, HasTranslations;

    public $translatable = ['label', 'description'];

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName(): string
    {
        return 'name';
    }

    public function loadUserStatistics(): self
    {
        if ($userId = Auth::id()) {
            $this->load(['userStatistics' => function ($query) use ($userId) {
                $query->where('user_id', $userId);
            }]);
        }

        return $this;
    }

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

    public function tags(): MorphToMany
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }

    public function userStatistics(): HasMany
    {
        return $this->hasMany(UserGameStatistic::class);
    }

    public function history(): HasMany
    {
        return $this->hasMany(History::class);
    }

    public function lastPlayedGame(): HasOne
    {
        return $this->history()->one()->latestOfMany();
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
