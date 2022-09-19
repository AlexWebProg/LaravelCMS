<?php

namespace App\Http\Controllers\Admin\Subscribe;

use App\Http\Requests\Admin\Subscribe\SendOneRequest;
use App\Http\Requests\Admin\Subscribe\SendManyRequest;
use App\Models\Subscribe;

class SendController extends BaseController
{
    /*
     * Отправка одной подписки
     * @param Subscribe $subscribe - подписка
     * @param SendRequest $request - данные из формы для отправки
     * @return int result - массив результатов
     */
    public function sendOneSubscribe(SendOneRequest $request, Subscribe $subscribe)
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
    }
    // --------------------------------------------------------------------------------------------

    /*
     * Отправка одной подписки
     * @param Subscribe $subscribe - подписка
     * @param SendRequest $request - данные из формы для отправки
     * @return int result - массив результатов
     */
    public function sendManySubscribes(SendManyRequest $request)
    {
        $arErrors = [];
        $arSendData = $request->validated();
        foreach ($arSendData['subscribe_id'] as $intSubscribeId) {
            $subscribe = Subscribe::find($intSubscribeId);
            $arResult = $this->service->sendSubscribe($subscribe,$arSendData);
            if (empty($arResult['intResult'])) {
                $arErrors = $arErrors + $arResult['arErrors'];
            } else {
                $bGotSent = true;
            }
        }
        if (count($arErrors)) {
            if (!empty($bGotSent)) $arErrors[] = 'Остальные подписки отправлены';
            $notification = [
                'class' => 'danger',
                'message' => implode('<br/>',$arErrors)
            ];
        } else {
            $notification = [
                'class' => 'success',
                'message' => 'Выбранные подписки успешно отправлены'
            ];
        }
        $request->session()->flash('notification', $notification);
    }
    // --------------------------------------------------------------------------------------------

}
