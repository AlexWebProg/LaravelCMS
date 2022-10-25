<?php

namespace App\Http\Controllers\Admin\Subscribe;

use App\Http\Requests\Admin\Subscribe\StoreRequest;
use App\Models\Subscribe;
use App\Models\SubscribeTilda;

class StoreController extends BaseController
{
    public function __invoke(StoreRequest $request)
    {
        $data = $request->validated();
        // Если был заказ из Тильды, помечаем его обработанным
        if (!empty($data['subscribe_tilda_id'])) {
            SubscribeTilda::where('id',$data['subscribe_tilda_id'])->update(['processed' => 1]);
            unset($data['subscribe_tilda_id']);
        }
        // Создаём подписку
        $subscribe = Subscribe::create($data);
        if (!empty($subscribe->id)) {
            return redirect()
                ->route('admin.subscribes.edit', $subscribe->id)
                ->with('notification',[
                    'class' => 'success',
                    'message' => 'Новая подписка успешно добавлена'
                ]);
        } else {
            return redirect()
                ->back()
                ->with('notification',[
                    'class' => 'danger',
                    'message' => 'При создании новой подписки произошла неизвестная ошибка.<br/>Подписка не была создана'
                ]);
        }

    }
}
