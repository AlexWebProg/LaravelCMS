<?php

namespace App\Http\Controllers\Admin\SubscribeTypeConsist;

use App\Models\SubscribeSettings;

class IndexController extends BaseController
{
    public function __invoke($month)
    {
        $subscribeTypes = SubscribeSettings::where('type', 'subscribe_type')
            ->orderBy('sort')
            ->orderBy('value')
            ->get();
        return view('admin.subscribeTypeConsist.index', compact('subscribeTypes', 'month'));
    }
}
