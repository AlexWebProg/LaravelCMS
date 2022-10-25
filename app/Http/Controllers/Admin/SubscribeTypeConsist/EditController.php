<?php

namespace App\Http\Controllers\Admin\SubscribeTypeConsist;

use App\Models\SubscribeSettings;
use App\Models\SubscribeProduct;

class EditController extends BaseController
{
    public function __invoke(SubscribeSettings $subscribeType, string $month)
    {
        $subscribeProducts = SubscribeProduct::all();
        return view('admin.subscribeTypeConsist.edit', compact('subscribeType', 'subscribeProducts', 'month'));
    }
}
