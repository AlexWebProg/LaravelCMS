<?php

namespace App\Http\Controllers\Admin\SubscribeSettings;

use App\Http\Controllers\Controller;
use App\Models\Subscribe;
use App\Models\SubscribeSettings;

class BaseController extends Controller
{
    /*
     * Проверяет использование параметра в подписках
     * @param int $id - ID параметра
     */
    public static function checkParamUse($id){
        $intResult = 0;
        $subscribeSetting = SubscribeSettings::find($id);
        if (!empty($subscribeSetting) and !empty($subscribeSetting->type)) {
            $subscribe = Subscribe::where($subscribeSetting->type.'_id', $id)->first();
            if (!empty($subscribe)) {
                $intResult = 1;
            }
        }
        return $intResult;
    }

}
