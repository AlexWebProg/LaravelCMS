<?php

namespace App\Http\Controllers\Admin\SubscribeProduct;

use App\Models\SubscribeProduct;

class DeleteController extends BaseController
{
    public function __invoke(SubscribeProduct $subscribeProduct)
    {
        if ($subscribeProduct->delete()) {
            return redirect()
                ->route('admin.subscribeProducts.index')
                ->with('notification',[
                    'class' => 'success',
                    'message' => 'Товар успешно удалён'
                ]);
        } else {
            return redirect()
                ->back()
                ->with('notification',[
                    'class' => 'danger',
                    'message' => 'При удалении товара произошла неизвестная ошибка'
                ]);
        }
    }
}
