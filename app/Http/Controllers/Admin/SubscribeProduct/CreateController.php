<?php

namespace App\Http\Controllers\Admin\SubscribeProduct;

class CreateController extends BaseController
{
    public function __invoke()
    {
        return view('admin.subscribeProducts.create');
    }
}
