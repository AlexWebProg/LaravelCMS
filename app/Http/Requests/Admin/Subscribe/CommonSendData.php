<?php
namespace App\Http\Requests\Admin\Subscribe;
use App\Rules\DateMonthYear;

trait CommonSendData
{

    protected function commonRules()
    {
        return [
            'sending_date' => 'required|date',
            'sender' => 'required|string',
            'sent_month' => ['required', new DateMonthYear],
        ];
    }

    protected function commonMessages()
    {
        return [
            'sending_date.required' => 'Это поле необходимо заполнить',
            'sending_date.date' => 'Это поле должно быть датой в формате дд.мм.гггг чч:мм',

            'sender.required' => 'Это поле необходимо заполнить',
            'sender.string' => 'Это поле должно быть текстом',

            'sent_month.required' => 'Это поле необходимо заполнить',
        ];
    }
}