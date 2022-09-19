<?php

namespace App\Http\Controllers\Admin\Subscribe;

use App\Models\Subscriber;

class ChooseSubscriberController extends BaseController
{
    public function __invoke()
    {
        $subscribers = Subscriber::where('subscriber',1)->orderByDesc('user_id')->get();
        $arSubscribers = [];
        foreach ($subscribers as $subscriber) {
            $arSubscribers[] = [
                'user_id' => $subscriber->user_id,
                'email_or_phone' => $subscriber->email
            ];
            $arSubscribers[] = [
                'user_id' => $subscriber->user_id,
                'email_or_phone' => $subscriber->phone
            ];
            $arSubscribers[] = [
                'user_id' => $subscriber->user_id,
                'email_or_phone' => $subscriber->phone_str
            ];
        }
        return view('admin.subscribes.choose_subscriber',compact('arSubscribers'));
    }
}
