<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    protected $guarded = [
        'id',
        'email_verified_at',
        'created_at',
        'updated_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function loadWithRelations(): self
    {
        return $this->load('latestCheckpoint.stages.category');
    }

    public function loadLatestCheckpointRelations(): self
    {
        return $this->load([
            'latestUncompletedCheckpoint' => [
                 'stages' => [
                      'category',
                     ],
                ],
            ]);
    }


    /**
     * @return HasMany
     */
    public function checkpoints(): HasMany
    {
        return $this->hasMany(Checkpoint::class);
    }

    public function stages(): HasManyThrough
    {
        return $this->throughCheckpoints()->hasStages();
    }

    public function latestCheckpoint(): HasOne
    {
        return $this->checkpoints()->one()->latestOfMany();
    }

    public function latestUncompletedCheckpoint(): HasOne
    {
        return $this->checkpoints()->one()->ofMany(['id' => 'max'], function(EloquentBuilder $query) {
            $query->where('is_completed', false);
        });
    }

    public function latestUncompletedStages(): HasManyThrough
    {
        // todo: this implementation is here for performance comparison
        // Using whereIn even though the subquery returns a single value.
        // This ensures all conditions are preserved
        //        return $this->stages()
        //            ->whereIn('checkpoints.id', $this->latestUncompletedCheckpoint()->select('id')->getQuery())
        //            ->where('checkpoint_stages.is_completed', false);

        return $this->stages()
            ->where('checkpoint_stages.is_completed', false)
            ->where('checkpoints.id', function (QueryBuilder $query) {
                $query->select('id')
                    ->from('checkpoints')
                    ->where('checkpoints.user_id', $this->id)
                    ->where('is_completed', false)
                    ->orderByDesc('id')
                    ->take(1);
            });
    }
}
