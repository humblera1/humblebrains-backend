<?php

namespace App\Models\Traits\Scopes;

use Illuminate\Database\Eloquent\Builder;

trait WithCompletedScope
{
    /**
     * Scope a query to only include completed items.
     */
    public function scopeCompleted(Builder $query): Builder
    {
        return $query->where('is_completed', true);
    }

    /**
     * Scope a query to only include uncompleted items.
     */
    public function scopeUncompleted(Builder $query): Builder
    {
        return $query->where('is_completed', false);
    }
}
