<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Word extends Model
{
    use HasFactory, HasTranslations;

    public $translatable = ['word'];

    protected $fillable = ['word'];
}
