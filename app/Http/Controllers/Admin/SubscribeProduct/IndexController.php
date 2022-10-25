<?php

namespace App\Http\Controllers\Admin\SubscribeProduct;

use App\Models\SubscribeProduct;

class IndexController extends BaseController
{
    public function __invoke()
    {
        $subscribeProducts = SubscribeProduct::all();
        return view('admin.subscribeProducts.index', compact('subscribeProducts'));
    }
}
