<?php

namespace App\Http\Controllers\Admin\Subscriber;

use App\Http\Controllers\Controller;

class CreateController extends BaseController
{
    public function __invoke()
    {
        return view('admin.subscribers.create');
    }
}
