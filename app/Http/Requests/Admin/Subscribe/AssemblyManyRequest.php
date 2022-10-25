<?php

namespace App\Http\Requests\Admin\Subscribe;

use Illuminate\Foundation\Http\FormRequest;

class AssemblyManyRequest extends FormRequest
{
    use CommonAssemblyData;
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
        return array_merge($this->commonRules(), [
            'subscribe_id'    => 'required|array',
            'subscribe_id.*' => 'integer|exists:mysql_no_prefix.loncq_user_subscribes,id',
        ]);
    }

    public function messages()
    {
        return array_merge($this->commonMessages(), [
            'subscribe_id.required' => 'Не выбраны подписки',
            'subscribe_id.array' => 'С выбранными подписками что-то не так',
            'subscribe_id.*.integer' => 'С выбранными подписками что-то не так',
            'subscribe_id.*.exists' => 'Не все выбранные подписки найдены в системе',
        ]);
    }
}
