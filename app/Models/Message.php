<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Message extends Model
{
    use HasFactory, HasTranslations;

    public $translatable = ['content'];

    public function getMessagePluralForm(int $count): string
    {
        return trans_choice($this->content, $count);
    }
}
