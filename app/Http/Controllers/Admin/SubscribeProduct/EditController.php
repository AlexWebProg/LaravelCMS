<?php

namespace App\Http\Controllers\Admin\SubscribeProduct;

use App\Models\SubscribeProduct;

class EditController extends BaseController
{
    public function __invoke(SubscribeProduct $subscribeProduct)
    {
        return view('admin.subscribeProducts.edit', compact('subscribeProduct'));
    }
}
