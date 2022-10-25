<?php

namespace App\Http\Controllers\Admin\SubscribeTypeConsist;

use App\Http\Requests\Admin\SubscribeTypeConsist\UpdateRequest;
use App\Models\SubscribeSettings;

class UpdateController extends BaseController
{
    public function __invoke(UpdateRequest $request, SubscribeSettings $subscribeType, string $month)
    {

        $data = $request->validated();

        $arNotification = $this->service->updateSubscribeTypeConsist($subscribeType,$month,$data);

        return redirect()
            ->back()
            ->with('notification',$arNotification);

    }
}
