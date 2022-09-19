<?php

namespace App\Http\Controllers\Admin\Subscriber;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Subscriber\UpdateRequest;
use App\Models\Subscriber;

class UpdateController extends BaseController
{
    public function __invoke(UpdateRequest $request, Subscriber $subscriber)
    {
        $data = $request->validated();
        $subscriber->update($data);
        return redirect()
            ->route('admin.subscribers.edit', $subscriber->user_id)
            ->with('notification',[
                'class' => 'success',
                'message' => 'Данные успешно обновлены'
            ]);
    }
}
