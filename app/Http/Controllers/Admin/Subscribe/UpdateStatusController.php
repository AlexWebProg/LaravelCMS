<?php

namespace App\Http\Controllers\Admin\Subscribe;

use App\Http\Requests\Admin\Subscribe\UpdateStatusRequest;
use App\Models\Subscribe;

class UpdateStatusController extends BaseController
{
    public function __invoke(UpdateStatusRequest $request, Subscribe $subscribe)
    {
        $data = $request->validated();

        // Обновляем подписку и пишем историю
        if ($this->service->updateStatus($subscribe,$data)) {
            return redirect()
                ->route('admin.subscribes.edit', $subscribe->id)
                ->with('notification',[
                    'class' => 'success',
                    'message' => $data['action_type']. ' успешно выполнена'
                ]);
        } else {
            return redirect()
                ->back()
                ->with('notification',[
                    'class' => 'danger',
                    'message' => $data['action_type']. ' не была выполнена. Попробуйте обновить страницу и выполнить действие ещё раз'
                ]);
        }

    }
}
