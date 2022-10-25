<?php

namespace App\Http\Controllers\Admin\SubscribeProduct;

use App\Http\Requests\Admin\SubscribeProduct\UpdateRequest;
use App\Models\SubscribeProduct;

class UpdateController extends BaseController
{
    public function __invoke(UpdateRequest $request, SubscribeProduct $subscribeProduct)
    {
        $data = $request->validated();

        if ($subscribeProduct->update($data)) {
            return redirect()
                ->back()
                ->with('notification',[
                    'class' => 'success',
                    'message' => 'Данные успешно обновлены'
                ]);
        } else {
            return redirect()
                ->back()
                ->with('notification',[
                    'class' => 'danger',
                    'message' => 'При обновлении данных произошла неизвестная ошибка'
                ]);
        }
    }
}
