<?php

namespace App\Http\Requests\Admin\User;

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

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users,email,' .$this->user_id,
            'role' => 'required|integer',
            'user_id' => 'required|integer|exists:users,id',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Это поле необходимо заполнить',
            'name.string' => 'Это поле должно быть текстом',
            'email.required' => 'Это поле необходимо заполнить',
            'email.string' => 'Это поле должно быть текстом',
            'email.email' => 'Это поле должно соответствовать формату email',
            'email.unique' => 'Пользователь с таким email уже существует',
        ];
    }
}
