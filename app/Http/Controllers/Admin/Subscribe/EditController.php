<?php

namespace App\Http\Controllers\Admin\Subscribe;

use App\Models\Subscribe;
use App\Models\SubscribeSettings;

class EditController extends BaseController
{
    public function __invoke(Subscribe $subscribe, $action_type = 'main_form')
    {
        $arSettings = [];
        $subscribeSettings = SubscribeSettings::where('is_active', 1)
            ->orderBy('type')
            ->orderBy('sort')
            ->get();
        foreach ($subscribeSettings as $subscribeSetting){
            $arSettings[$subscribeSetting->type][] = $subscribeSetting;
        }
        $arAllStatuses = Subscribe::getAllStatuses();
        foreach ($arAllStatuses as $arStatus){
            $arSettings['status'][] = $arStatus;
        }
        return view('admin.subscribes.edit.'.$action_type, compact('subscribe', 'arSettings'));
    }
}
