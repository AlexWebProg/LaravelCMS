<?php

namespace App\Http\Controllers\Admin\Subscribe;

use App\Models\Subscribe;
use App\Models\Subscriber;
use App\Models\SubscribeSettings;
use App\Models\SubscribeTilda;

class CreateController extends BaseController
{
    public function __invoke(Subscriber $subscriber, SubscribeTilda $orderFromTilda = null)
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

        // Данные из заказа из Тильды
        $arTildaOrderSettings = $this->getTildaOrderSettings($orderFromTilda);

        return view('admin.subscribes.create', compact('arSettings', 'subscriber', 'arTildaOrderSettings'));
    }

    // Данные из заказа из Тильды
    private function getTildaOrderSettings($orderFromTilda){
        $arTildaOrderSettings = [
            'id' => !empty($orderFromTilda->id) ? $orderFromTilda->id : 0,
            'type' => 0,
            'pref_acc' => 0,
            'periodicity' => 0,
            'size_top' => 0,
            'size_bottom' => 0,
            'size_height' => 0,
            'size_foot' => 0,
            'comment_subscriber' => '',
            'delivery_addr' => '',
            'delivery_type' => 0,
        ];
        // Тип подписки
        if (!empty($orderFromTilda->data['subscribe']['Тип подписки'])) {
            $STSettings = SubscribeSettings::where('is_active', 1)
                ->where('type','subscribe_type')
                ->where('value',$orderFromTilda->data['subscribe']['Тип подписки'])
                ->get();
            if (!empty($STSettings[0]->id)) {
                $arTildaOrderSettings['type'] = $STSettings[0]->id;
            }
            unset($STSettings);
        }
        // Учёт предпочтений
        if (isset($orderFromTilda->data['subscribe']['Учет_предпочтений_сумма'])) {
            $STSettings = SubscribeSettings::where('is_active', 1)
                ->where('type','pref_acc')
                ->where('cost',$orderFromTilda->data['subscribe']['Учет_предпочтений_сумма'])
                ->get();
            if (!empty($STSettings[0]->id)) {
                $arTildaOrderSettings['pref_acc'] = $STSettings[0]->id;
            }
            unset($STSettings);
        }
        // Периодичность
        if (!empty($orderFromTilda->data['subscribe']['Периодичность'])) {
            $STSettings = SubscribeSettings::where('is_active', 1)
                ->where('type','periodicity')
                ->where('value',$orderFromTilda->data['subscribe']['Периодичность'])
                ->get();
            if (!empty($STSettings[0]->id)) {
                $arTildaOrderSettings['periodicity'] = $STSettings[0]->id;
            }
            unset($STSettings);
        }
        // Размер верха
        if (!empty($orderFromTilda->data['subscribe']['Размер верха'])) {
            $STSettings = SubscribeSettings::where('is_active', 1)
                ->where('type','size_top')
                ->where('value',$orderFromTilda->data['subscribe']['Размер верха'])
                ->get();
            if (!empty($STSettings[0]->id)) {
                $arTildaOrderSettings['size_top'] = $STSettings[0]->id;
            }
            unset($STSettings);
        }
        // Размер низа
        if (!empty($orderFromTilda->data['subscribe']['Размер низа'])) {
            $STSettings = SubscribeSettings::where('is_active', 1)
                ->where('type','size_bottom')
                ->where('value',$orderFromTilda->data['subscribe']['Размер низа'])
                ->get();
            if (!empty($STSettings[0]->id)) {
                $arTildaOrderSettings['size_bottom'] = $STSettings[0]->id;
            }
            unset($STSettings);
        }
        // Ваш рост
        if (!empty($orderFromTilda->data['subscribe']['Ваш рост'])) {
            $STSettings = SubscribeSettings::where('is_active', 1)
                ->where('type','size_height')
                ->where('value',$orderFromTilda->data['subscribe']['Ваш рост'])
                ->get();
            if (!empty($STSettings[0]->id)) {
                $arTildaOrderSettings['size_height'] = $STSettings[0]->id;
            }
            unset($STSettings);
        }
        // Размер стопы
        if (!empty($orderFromTilda->data['subscribe']['Размер стопы'])) {
            $STSettings = SubscribeSettings::where('is_active', 1)
                ->where('type','size_foot')
                ->where('value',$orderFromTilda->data['subscribe']['Размер стопы'])
                ->get();
            if (!empty($STSettings[0]->id)) {
                $arTildaOrderSettings['size_foot'] = $STSettings[0]->id;
            }
            unset($STSettings);
        }
        // Комментарий подписчика
        if (!empty($orderFromTilda->data['payment']['delivery_comment'])) {
            $arTildaOrderSettings['comment_subscriber'] = $orderFromTilda->data['payment']['delivery_comment'];
        }
        // Адрес доставки
        if (!empty($orderFromTilda->data['payment']['delivery_address'])) {
            $arTildaOrderSettings['delivery_addr'] = $orderFromTilda->data['payment']['delivery_address'];
        }
        // Тип доставки
        if (!empty($orderFromTilda->data['payment']['delivery'])) {
            $STSettings = SubscribeSettings::where('is_active', 1)
                ->where('type','delivery_type')
                ->where('value',$orderFromTilda->data['payment']['delivery'])
                ->get();
            if (!empty($STSettings[0]->id)) {
                $arTildaOrderSettings['delivery_type'] = $STSettings[0]->id;
            }
            unset($STSettings);
        }
        return $arTildaOrderSettings;
    }
}
