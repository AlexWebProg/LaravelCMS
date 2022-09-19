<?php

namespace App\Http\Controllers\Admin\SubRequest;

use App\Http\Controllers\Controller;
use App\Models\SubRequest;

class IndexController extends Controller
{
    public function __invoke($status = 'allOpened')
    {
        $pageTitle = 'Заявки по подпискам';
        foreach (SubRequest::getStatuses() as $arStatusInfo) {
            if ($arStatusInfo['status_code'] == $status) {
                $pageTitle = $arStatusInfo['index_page_title'];
                break;
            }
        }
        $subRequests = SubRequest::getSubRequestsByStatusCode($status);
        return view('admin.subRequests.index', compact('subRequests', 'pageTitle', 'status'));
    }
}
