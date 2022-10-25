<?php
namespace App\Http\Requests\Admin\SubscribeProduct;

trait CommonStoreUpdateData
{

    protected function commonRules()
    {
        return [
            'name' => 'required|string',
            'article' => 'required|string',
        ];
    }

    protected function commonMessages()
    {
        return [
            'name.required' => 'Это поле необходимо заполнить',
            'name.string' => 'Это поле должно быть текстом',

            'article.required' => 'Это поле необходимо заполнить',
            'article.string' => 'Это поле должно быть текстом',
        ];
    }
}