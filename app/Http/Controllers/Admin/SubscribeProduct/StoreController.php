<?php

namespace App\Http\Controllers\Admin\SubscribeProduct;

use App\Http\Requests\Admin\SubscribeProduct\StoreRequest;
use App\Models\SubscribeProduct;

class StoreController extends BaseController
{
    public function __invoke(StoreRequest $request)
    {
        $data = $request->validated();

        if (SubscribeProduct::create($data)) {
            return redirect()
                ->route('admin.subscribeProducts.index')
                ->with('notification',[
                    'class' => 'success',
                    'message' => 'Новый товар успешно добавлен'
                ]);
        } else {
            return redirect()
                ->back()
                ->with('notification',[
                    'class' => 'danger',
                    'message' => 'При создании нового товара произошла неизвестная ошибка.<br/>Товар не был создан'
                ]);
        }

    }
}
