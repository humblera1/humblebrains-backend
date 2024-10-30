<?php

namespace App\Enums\Game;

use App\Models\Traits\Enums\WithValues;

/**
 * Used to simplify working with categories (in tests for example).
 * Must be kept up to date with categories table.
 */
enum CategoryEnum: string
{
    use WithValues;

    case Logic = 'logic';
    case Memory = 'memory';
    case Attention = 'attention';
}
