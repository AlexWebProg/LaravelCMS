<?php

namespace App\Http\Controllers\Admin\SubscribeConsist;

use App\Models\Subscribe;
use App\Models\SubscribeProduct;

class EditController extends BaseController
{
    public function __invoke(Subscribe $subscribe, string $month)
    {
        $subscribeProducts = SubscribeProduct::all();
        return view('admin.subscribeConsist.edit', compact('subscribe', 'subscribeProducts', 'month'));
    }
}
