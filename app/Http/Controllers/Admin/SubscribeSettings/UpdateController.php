<?php

namespace App\Http\Controllers\Admin\SubscribeSettings;

use App\Models\SubscribeSettings;
use App\Models\Subscribe;
use Illuminate\Http\Request;

class UpdateController extends BaseController
{
    // $type - тип параметров
    public function __invoke(Request $request, $type)
    {
        $arErrors = [];
        if (!empty($request->id) && is_array($request->id) && count($request->id)) {
            foreach ($request->id as $intSort => $id) {
                $strValue = trim($request->value[$intSort]);
                if (empty($type)) {
                    $arErrors[] = 'Не был передан тип параметра';
                }elseif (empty($strValue)) {
                    $arErrors[] = 'Значение параметра не может быть пустым. Эта строка не была сохранена';
                }elseif (!empty($id) and !isset($request->is_active[$intSort]) and !empty(self::checkParamUse($id))) {
                    $arErrors[] = 'Значение "'.$strValue.'" используется в подписках, оно не может быть неактивным. Эта строка не была сохранена';
                }else{
                    if (empty($id)) {
                        $setting = new SubscribeSettings;
                    } else {
                        $setting = SubscribeSettings::firstOrCreate(['id' => $id],['id' => $id]);
                    }
                    if (!empty($setting)) {
                        $setting->type = $type;
                        $setting->value = $strValue;
                        $setting->is_active = isset($request->is_active[$intSort]) ? 1 : 0;
                        $setting->sort = $intSort;
                        $setting->cost = isset($request->cost[$intSort]) ? (int) $request->cost[$intSort] : NULL;
                        $setting->save();
                        // Если изменяются даты отправки, то нужно пересчитать их для подписчика
                        if ($type === 'send_dates') {
                            foreach ($setting->subscibesBySendDatesSetting as $subscribe) $subscribe->save();
                        }
                    } else {
                        $arErrors[] = 'Не получилось сохранить значение "'.$strValue.'"';
                    }
                    unset($setting);
                }
                unset($strValue);
            }
        }

        $notification = [
            'class' => 'success',
            'message' => 'Данные успешно обновлены'
        ];

        if (count($arErrors)) {
            $notification = [
                'class' => 'danger',
                'message' => implode('<br/>',array_unique($arErrors))
            ];
        }

        return redirect()
            ->route('admin.subscribeSettings.index', $type)
            ->with('notification',$notification);

    }
}
