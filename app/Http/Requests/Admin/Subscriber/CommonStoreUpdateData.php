<?php
namespace App\Http\Requests\Admin\Subscriber;

trait CommonStoreUpdateData
{

    protected function commonRules()
    {
        return [
            'name' => 'required|string'
        ];
    }

    protected function commonMessages()
    {
        return [
            'name.required' => 'Это поле необходимо заполнить',
            'name.string' => 'Это поле должно быть текстом',

            'email.required' => 'Это поле необходимо заполнить',
            'email.email' => 'Это поле не похоже на адрес электронной почты',
            'email.unique' => 'Подписчик с таким email уже существует',

            'phone.required' => 'Это поле необходимо заполнить',
            'phone.numeric' => 'Это поле должно содержать только цифры',
            'phone.unique' => 'Подписчик с таким телефоном уже существует',
        ];
    }
}