<?php

namespace App\Http\Controllers\Admin\SubscribeSettings;

use Illuminate\Http\Request;
use App\Models\SubscribeSettings;

class DeleteController extends BaseController
{
    // Проверяем, использует ли какая-либо подписка параметр $id
    public function __invoke(Request $request)
    {
        $intResult = 0;
        if (!empty($request->id)) {
            if (empty(self::checkParamUse($request->id))) {
                $subscribeSetting = SubscribeSettings::find($request->id);
                if (!empty($subscribeSetting)) $subscribeSetting->delete();
                $intResult = 1;
            }
        }
        echo(json_encode($intResult));
    }

}
