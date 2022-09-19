<?php

namespace App\Http\Controllers\Admin\Subscribe;

use App\Http\Requests\Admin\Subscribe\SetSubscriberRequest;

class SetSubscriberController extends BaseController
{
    public function __invoke(SetSubscriberRequest $request)
    {
        $data = $request->validated();
        if (!empty($data['user_id'])) {
            return redirect()
                ->route('admin.subscribes.create', $data['user_id']);
        } else {
            return redirect()
                ->back()
                ->with('notification',[
                    'class' => 'danger',
                    'message' => 'Такой подписчик не найден'
                ]);
        }
    }
}
