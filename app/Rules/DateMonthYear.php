<?php

namespace App\Rules;

use Carbon\Carbon;
use Illuminate\Contracts\Validation\InvokableRule;

class DateMonthYear implements InvokableRule
{
    /**
     * Run the validation rule.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     * @return void
     */
    public function __invoke($attribute, $value, $fail)
    {
        try {
            Carbon::createFromLocaleIsoFormat('!MMMM YYYY', 'ru', $value, null);
        } catch(\Carbon\Exceptions\InvalidArgumentException $x) {
            $fail('Это поле должно быть датой в формате ММММ ГГГГ (месяц и год)');
        }

    }
}
