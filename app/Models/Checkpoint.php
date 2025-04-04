<?php

namespace App\Models;

use App\Models\Traits\Scopes\WithCompletedScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Checkpoint extends Model
{
    use HasFactory, WithCompletedScope;

    protected $fillable = [
        'user_id',
        'is_completed',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);

    }

    public function stages(): HasMany
    {
        return $this->hasMany(CheckpointStage::class);
    }
}
