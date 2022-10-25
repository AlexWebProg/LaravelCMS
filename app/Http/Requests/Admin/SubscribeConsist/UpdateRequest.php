<?php

namespace App\Http\Requests\Admin\SubscribeConsist;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
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

    public function rules()
    {
        return [
            'product_id.*' => 'required|integer|exists:mysql_no_prefix.loncq_subscribe_products,id',
            'qnt.*' => 'required|integer',
        ];
    }

    public function messages()
    {
        return [
            'product_id.*.required' => 'Это поле необходимо заполнить',
            'product_id.*.integer' => 'Это поле заполнено неверно',
            'product_id.*.exists' => 'Этот товар не найден в системе',

            'qnt.*.required' => 'Это поле необходимо заполнить',
            'qnt.*.integer' => 'Это поле заполнено неверно',
        ];
    }
}
