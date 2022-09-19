<?php

namespace App\Http\Controllers\Admin\SubRequest;

use App\Http\Controllers\Controller;
use App\Models\SubRequest;
use App\Models\SubRequestComment;
use Carbon\Carbon;

class ShowController extends Controller
{
    public function __invoke($status, SubRequest $subRequest)
    {
        // Статус заявок в хлебных крошках
        $srBreadCrumbName = 'Заявки по подпискам';
        foreach (SubRequest::getStatuses() as $arStatusInfo) {
            if ($arStatusInfo['status_code'] == $status) {
                $srBreadCrumbName = $arStatusInfo['index_page_title'];
                break;
            }
        }
        // Информация о заявке
        SubRequest::formatSubRequest($subRequest);
        // Комментарии к заявке
        $arComments = SubRequestComment::getSubRequestCommentsByRequestId($subRequest->id);
        if (!empty($arComments) and count($arComments)) {
            foreach ($arComments as &$arComment) {
                if (!empty($arComment->subscriber_user_id)) $arComment->name = $subRequest->subscribe_info['user_name'];
                $arComment->created = Carbon::parse($arComment->created)->translatedFormat('d.m.Y H:i');
            }
        }

        return view('admin.subRequests.show', compact(
            'status',
            'srBreadCrumbName',
            'subRequest',
            'arComments'
        ));
    }
}
