<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Translatable\HasTranslations;

class Message extends Model
{
    use HasFactory, HasTranslations;

    public $translatable = ['content'];

    public function getMessagePluralForm(int $count): string
    {
        $locale = \App::getLocale();

        return match ($locale) {
            'ru' => $this->getRussianPluralForm($count),
            'en' => $this->getEnglishPluralForm($count),
            default => trans_choice($this->content, $count),
        };
    }

    /**
     * Числа, оканчивающиеся на 1 (кроме 11): 1, 21, 31, 41, ..., 101, 121, ...
     * Числа, оканчивающиеся на 2, 3, 4 (кроме 12, 13, 14): 2-4, 22-24, 32-34, ..., 102-104, 122-124, ...
     * Числа, оканчивающиеся на 5-9, 0, а также 11-14: 0, 5-20, 25-30, 35-40, ..., 105-120, 125-130, ...
     *
     * {1}, [2,3,4], [5,*]
     *
     * @param int $count
     * @return string
     */
    private function getRussianPluralForm(int $count): string
    {
        $remainder10 = $count % 10;
        $remainder100 = $count % 100;

        if ($remainder100 >= 11 && $remainder100 <= 14) {
            return trans_choice($this->content, 5, ['count' => $count]);
        }

        return match ($remainder10) {
            1 => trans_choice($this->content, 1, ['count' => $count]),
            2, 3, 4 => trans_choice($this->content, 2, ['count' => $count]),
            default => trans_choice($this->content, 5, ['count' => $count]),
        };
    }

    /**
     * {1}, [2,*]
     *
     * @param int $count
     * @return string
     */
    private function getEnglishPluralForm(int $count): string
    {
        return trans_choice($this->content, $count, ['count' => $count]);
    }
}
