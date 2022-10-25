<?php
namespace App\Http\Requests\Admin\Subscribe;
use App\Rules\DateMonthYear;

trait CommonAssemblyData
{

    protected function commonRules()
    {
        return [
            'assembly_month' => ['required', new DateMonthYear],
        ];
    }

    protected function commonMessages()
    {
        return [
            'assembly_month.required' => 'Это поле необходимо заполнить',
        ];
    }
}
