<?php

namespace App\Http\Controllers\Admin\SubscribeConsist;

class IndexController extends BaseController
{
    public function __invoke($month)
    {
        return view('admin.subscribeConsist.index', compact('month'));
    }
}
