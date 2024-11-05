<?php

namespace App\Enums;

enum PeriodEnum: string
{
    case Week = 'week';
    case Month = 'month';
    case Year = 'year';
    case All = 'all';
}
