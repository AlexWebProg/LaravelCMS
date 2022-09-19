<?php

namespace App\Http\Requests\Admin\Subscribe;

use App\Rules\DateMonthYear;
use App\Rules\MobilePhone;
use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    use CommonStoreUpdateData;

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
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $this->merge([
            'sum' => floatval(str_replace([' ',','], ['','.'], $this->sum)),
            'subscriber_phone' => preg_replace('/[^[:digit:]]/', '', $this->subscriber_phone)
        ]);

    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return array_merge($this->commonRules(), [
            'status' => 'required|string|in:Активна,Заморожена,Отменена',
            'user_id' => 'required|integer|exists:mysql_no_prefix.loncq_user,user_id,subscriber,1',
            'next_send_month' => ['sometimes', 'required_if:status,Активна', new DateMonthYear],
        ]);
    }

    public function messages()
    {
        return array_merge($this->commonMessages(), [
            'user_id.required' => 'Проблема с подписчиком: подписчик не выбран',
            'user_id.integer' => 'Проблема с подписчиком: подписчик не выбран',
            'user_id.exists' => 'Проблема с подписчиком: этот подписчик не найден в системе',

            'status.required' => 'Присваиваемый подписке статус должен быть: Активна,Заморожена или Отменена',
            'status.string' => 'Присваиваемый подписке статус должен быть: Активна,Заморожена или Отменена',
            'status.in' => 'Присваиваемый подписке статус должен быть: Активна,Заморожена или Отменена',

            'next_send_month.required_if' => 'Это поле необходимо заполнить',
        ]);
    }
}
