<?php

namespace App\Http\Requests\Admin\Subscribe;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\DateMonthYear;
use Illuminate\Validation\Rule;

class UpdateStatusRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'user' => 'required|string',
            'action_type' => 'required|string',
            'status' => 'required|string|in:Активна,Заморожена,Отменена',
            'comment_manager' => 'nullable|string',
            'comment_subscriber' => 'nullable|string',
            'next_send_month' => ['required_if:status,Активна', new DateMonthYear],
        ];
    }

    public function messages()
    {
        return [
            'user.required' => 'Это поле необходимо заполнить',
            'user.string' => 'Это поле должно быть текстом',

            'action_type.required' => 'Не выбрано производимое действие. Попробуйте обновить страницу',
            'action_type.string' => 'Не выбрано производимое действие. Попробуйте обновить страницу',

            'status.required' => 'Присваиваемый подписке статус должен быть: Активна,Заморожена или Отменена',
            'status.string' => 'Присваиваемый подписке статус должен быть: Активна,Заморожена или Отменена',
            'status.in' => 'Присваиваемый подписке статус должен быть: Активна,Заморожена или Отменена',

            'comment_manager.string' => 'Это поле должно быть текстом',

            'comment_subscriber.string' => 'Это поле должно быть текстом',

            'next_send_month.required_if' => 'Это поле необходимо заполнить',
        ];
    }
}
