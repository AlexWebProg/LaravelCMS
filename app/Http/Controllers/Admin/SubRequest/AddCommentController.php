<?php

namespace App\Http\Controllers\Admin\SubRequest;

use App\Http\Requests\Admin\SubRequestComment\StoreRequest;
use App\Http\Controllers\Controller;
use App\Jobs\OpenExternalUrlJob;
use App\Models\SubRequest;
use App\Models\SubRequestComment;

class AddCommentController extends Controller
{
    public function __invoke(StoreRequest $request, SubRequest $subRequest)
    {
        // Создаём комментарий
        SubRequestComment::create([
            'request_id' => $subRequest->id,
            'bbadmin_user_id' => auth()->user()->id,
            'text' => $request->text,
            'created' => date('Y-m-d H:i:s',time())
        ]);

        // Обновляем дату и статус заявки
        $subRequest->updated = date('Y-m-d H:i:s',time());
        $subRequest->status = 1;
        $subRequest->save();

        // Создаём оповещение для подписчика о том, что его заявка обновлена. Используем очередь
        OpenExternalUrlJob::dispatch(env('BB_STORE_URL').'/api/makeSubscriberSubRequestUpdateNotification/'.$subRequest->user_id.'/'.$subRequest->id);

        return redirect()->to(route(
            'admin.subRequests.show',
            [
                SubRequest::getStatusCode($subRequest->status),
                $subRequest->id
            ]
        ).'#page_bottom');
    }
}
