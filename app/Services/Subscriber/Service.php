<?php

namespace App\Services\Subscriber;

use App\Jobs\OpenExternalUrlJob;
use App\Models\Subscriber;

class Service
{
    // Обновляет существующего подписчика, или создаёт нового
    public function addOrUpdateSubscriber($data) {
        $subscriber = Subscriber::getSubscriber($data['email'],$data['phone']);
        $arSubscriberData = [
            'email' => $data['email'],
            'phone' => $data['phone'],
            'name' => $data['name'],
            'subscriber' => 1
        ];
        // Создаём, или обновляем подписчика
        if (empty($subscriber->user_id)) {
            $subscriber = Subscriber::addSubscriber($arSubscriberData);
            // Отправляем письмо о регистрации на сайте подписчику
            OpenExternalUrlJob::dispatch(env('BB_STORE_URL').'/api/makeSubscriberCreatedNotification/'.$subscriber->user_id);
        } else {
            $subscriber->update($arSubscriberData);
        }
        return $subscriber;
    }
}