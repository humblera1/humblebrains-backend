<?php

namespace App\Models;

use App\Events\HistorySaved;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class History extends Model
{
    use HasFactory;

    protected $table = 'history';

    public $timestamps = false;

    protected $dispatchesEvents = [
        'saved' => HistorySaved::class,
    ];
}
