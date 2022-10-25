<?php

namespace App\Http\Controllers\Admin\Subscriber;

use App\Http\Requests\Admin\Subscriber\StoreRequest;

class StoreController extends BaseController
{
    public function __invoke(StoreRequest $request)
    {
        $data = $request->validated();
        $subscriber = $this->service->addOrUpdateSubscriber($data);
        if (!empty($subscriber->user_id)) {
            return redirect()
                ->route('admin.subscribers.edit', $subscriber->user_id)
                ->with('notification',[
                    'class' => 'success',
                    'message' => 'Новый подписчик успешно добавлен'
                ]);
        } else {
            return redirect()
                ->back()
                ->with('notification',[
                    'class' => 'danger',
                    'message' => 'При создании нового подписчика произошла неизвестная ошибка.<br/>Подписчик не был создан'
                ]);
        }

    }
}
