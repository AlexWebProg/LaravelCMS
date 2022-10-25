<?php

namespace App\Http\Controllers\Admin\Subscribe;

use App\Http\Requests\Admin\Subscribe\SendOneRequest;
use App\Http\Requests\Admin\Subscribe\AssemblyManyRequest;
use App\Models\Subscribe;

class AssemblyController extends BaseController
{
    /*
     * Отправка одной подписки
     * @param Subscribe $subscribe - подписка
     * @param SendRequest $request - данные из формы для отправки
     * @return int result - массив результатов
     */
    /*public function sendOneSubscribe(SendOneRequest $request, Subscribe $subscribe)
    {
        $arSendData = $request->validated();
        $arResult = $this->service->sendSubscribe($subscribe,$arSendData);

        if ($arResult['intResult']) {
            return redirect()
                ->route('admin.subscribes.edit', $subscribe->id)
                ->with('notification',[
                    'class' => 'success',
                    'message' => 'Подписка успешно отправлена'
                ]);
        } else {
            return redirect()
                ->back()
                ->with('notification',[
                    'class' => 'danger',
                    'message' => implode('<br/>',$arResult['arErrors'])
                ]);
        }
    }*/
    // --------------------------------------------------------------------------------------------

    /*
     * Отправка одной подписки
     * @param Subscribe $subscribe - подписка
     * @param SendRequest $request - данные из формы для отправки
     * @return int result - массив результатов
     */
    public function assemblyManySubscribes(AssemblyManyRequest $request)
    {
        $arErrors = [];
        $arAssemblyData = $request->validated();
        foreach ($arAssemblyData['subscribe_id'] as $intSubscribeId) {
            $subscribe = Subscribe::find($intSubscribeId);
            $arResult = $this->service->assemblySubscribe($subscribe,$arAssemblyData);
            if (empty($arResult['intResult'])) {
                if (!empty($arResult['arErrors']) and count($arResult['arErrors'])) {
                    foreach ($arResult['arErrors'] as $strError) {
                        $arErrors[] = $strError;
                    }
                }
            } else {
                $bGotSent = true;
            }
        }
        if (count($arErrors)) {
            if (!empty($bGotSent)) $arErrors[] = 'Остальные подписки помечены на сборку';
            $notification = [
                'class' => 'danger',
                'message' => implode('<br/>',$arErrors)
            ];
            $result = 0;
        } else {
            $notification = [
                'class' => 'success',
                'message' => 'Выбранные подписки успешно помечены на сборку'
            ];
            $result = 1;
            $request->session()->flash('notification', $notification);
        }
        return ['result' => $result, 'message' => $notification['message'],  'redirect' => route('admin.subscribeConsist.index', monthStrToInt($arAssemblyData['assembly_month']))];
    }
    // --------------------------------------------------------------------------------------------

}
