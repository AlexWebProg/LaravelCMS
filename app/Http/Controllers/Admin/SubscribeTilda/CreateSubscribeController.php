<?php

namespace App\Http\Controllers\Admin\SubscribeTilda;

use App\Models\SubscribeTilda;

class CreateSubscribeController extends BaseController
{

    public function __invoke(SubscribeTilda $orderFromTilda)
    {
        // Проверяем, не обработан ли уже этот заказ
        if (!empty($orderFromTilda->processed)) {
            return redirect()
                ->back()
                ->with('notification',[
                    'class' => 'danger',
                    'message' => 'Этот заказ уже обработан'
                ]);
        } else {
            // Проверяем, существует ли подписчик. Если нет - создаём
            $arSubscriberData = [
                'name' => $orderFromTilda->data['Name'],
                'phone' => preg_replace('/[^[:digit:]]/', '', $orderFromTilda->data['phone']),
                'email' => $orderFromTilda->data['email'],
            ];
            $subscriber = $this->subscriber_service->addOrUpdateSubscriber($arSubscriberData);
            return redirect()
                ->route('admin.subscribes.create', [$subscriber->user_id, $orderFromTilda->id]);
        }
    }
    // ----------------------------------------------------------------------------------------------

}
