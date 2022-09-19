<?php

namespace App\Http\Controllers\Admin\SubRequest;

use App\Http\Controllers\Controller;
use App\Models\SubRequest;
use App\Models\SubRequestComment;

class CloseController extends Controller
{
    public function __invoke(SubRequest $subRequest)
    {
        // Статус заявки на текущий момент
        $statusCode = SubRequest::getStatusCode($subRequest->status);

        // Создаём комментарий "Заявка закрыта"
        SubRequestComment::create([
            'request_id' => $subRequest->id,
            'bbadmin_user_id' => auth()->user()->id,
            'text' => 'Заявка закрыта',
            'created' => date('Y-m-d H:i:s',time())
        ]);

        // Обновляем дату и статус заявки
        $subRequest->updated = date('Y-m-d H:i:s',time());
        $subRequest->status = 3;
        $subRequest->save();

        // Делаем переадресацию к списку заявок в статусе этой до закрытия
        return redirect()->route(
            'admin.subRequests.index', $statusCode);
    }
}
