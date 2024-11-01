<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProgramSession extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'program_id',
    ];

    public function program(): BelongsTo
    {
        return $this->belongsTo(Program::class);
    }
}
