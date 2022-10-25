<?php

namespace App\Http\Controllers\Admin\SubscribeConsist;

use App\Http\Requests\Admin\SubscribeConsist\UpdateRequest;
use App\Models\Subscribe;

class UpdateController extends BaseController
{
    public function __invoke(UpdateRequest $request, Subscribe $subscribe, string $month)
    {

        $data = $request->validated();

        $arNotification = $this->service->updateSubscribeConsist($subscribe,$month,$data);

        return redirect()
            ->back()
            ->with('notification',$arNotification);

    }
}
