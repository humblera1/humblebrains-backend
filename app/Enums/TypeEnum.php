<?php

namespace App\Enums;

use App\Models\Traits\Enums\WithValues;

/**
 * This enumeration is used in migration when creating a table of properties to limit their possible types.
 *
 * If this enumeration changes, you must update the table with game properties
 */
enum TypeEnum: string
{
    use WithValues;

    case Boolean = 'boolean';

    case Integer = 'integer';

    case Float = 'float';

    case String = 'string';

    case Array = 'array';
}
