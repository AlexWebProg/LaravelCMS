<?php

namespace App\Http\Controllers\Admin\Subscriber;

use App\Http\Controllers\Controller;
use App\Models\Subscriber;

class IndexController extends BaseController
{
    public function __invoke()
    {
        $subscribers = Subscriber::getAllSubscribers();
        return view('admin.subscribers.index', compact('subscribers'));
    }
}
