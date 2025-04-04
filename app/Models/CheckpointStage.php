<?php

namespace App\Models;

use App\Models\Traits\Scopes\WithCompletedScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CheckpointStage extends Model
{
    use HasFactory, WithCompletedScope;

    protected $fillable = [
        'checkpoint_id',
        'category_id',
        'score',
        'is_completed',
    ];

    public function checkpoint(): BelongsTo
    {
        return $this->belongsTo(Checkpoint::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
