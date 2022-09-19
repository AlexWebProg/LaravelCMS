<?php

namespace App\Http\Controllers\Admin\SubscribeSettings;

use Illuminate\Http\Request;

class CheckUseController extends BaseController
{
    // Проверяем, использует ли какая-либо подписка параметр $id
    public function __invoke(Request $request)
    {
        if (empty($request->id)) {
            $intResult = 1;
        } else {
            $intResult = self::checkParamUse($request->id);
        }
        echo(json_encode($intResult));
    }

}
