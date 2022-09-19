<?php

namespace App\Http\Controllers\Admin\Subscriber;

use App\Http\Controllers\Controller;
use App\Models\Subscriber;

class EditController extends BaseController
{
    public function __invoke(Subscriber $subscriber)
    {
        $arSubscribes = $subscriber->subscribes;
        return view('admin.subscribers.edit', compact('subscriber', 'arSubscribes'));
    }
}
