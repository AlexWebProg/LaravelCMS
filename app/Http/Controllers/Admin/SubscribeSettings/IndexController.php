<?php

namespace App\Http\Controllers\Admin\SubscribeSettings;

use App\Http\Controllers\Controller;
use App\Models\SubscribeSettings;

class IndexController extends Controller
{
    public function __invoke($type = 'periodicity')
    {
        $arSubscribeSettingsTypes = SubscribeSettings::getTypes();
        $pageTitle = 'Параметры подписок';
        foreach (SubscribeSettings::getTypes() as $arType) {
            if ($arType['code'] == $type) {
                $pageTitle = 'Параметры подписок: '.$arType['full_name'];
                break;
            }
        }
        $subscribeSettings = SubscribeSettings::where('type', $type)
            ->orderBy('sort')
            ->orderBy('value')
            ->get();
        return view('admin.subscribeSettings.index', compact('subscribeSettings', 'pageTitle', 'type', 'arSubscribeSettingsTypes'));

    }
}
