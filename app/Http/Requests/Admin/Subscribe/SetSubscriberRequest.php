<?php
namespace App\Http\Requests\Admin\Subscribe;

use Illuminate\Foundation\Http\FormRequest;

class SetSubscriberRequest extends FormRequest
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
            'user_id' => 'required|integer|exists:mysql_no_prefix.loncq_user,user_id,subscriber,1',
        ];
    }

    public function messages()
    {
        return [
            'user_id.required' => 'Пожалуйста, выберите email или телефон подписчика',
            'user_id.integer' => 'Проверьте заполнение поля. Подписчик не выбран',
            'user_id.exists' => 'Подписчик с таким email или телефоном не найден',
        ];
    }
}