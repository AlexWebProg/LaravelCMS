<?php

namespace App\Http\Controllers\Admin\Subscribe;

use App\Models\Subscribe;

class IndexJSONController extends BaseController
{

    public function __invoke()
    {
        // Все подписки
        $subscribes = Subscribe::all();
        // Добавляем подпискам сортировку статуса для фильтра
        $this->setStatusSort($subscribes);
        // Ближайшие месяцы для отправки
        $arPlannedDataLine = Subscribe::getPlannedDataLine();
        // Даты отправки в кажом месяце в таблице
        $this->setEstimatedSendsMonthDays($subscribes, $arPlannedDataLine);
        $arSubscribes = [];
        foreach ($subscribes as $subscribe) {
            $arSubscribe = [
                'checkbox' => '<input type="checkbox" class="subscribe_checkbox" value="'.$subscribe->id.'">',
                'subscribe_date' => [
                    'display' => $subscribe->subscribe_date_str,
                    'order' => $subscribe->getRawOriginal('subscribe_date')
                ],
                'status' => [
                    'display' => $subscribe->status,
                    'order' => $subscribe->status_sort
                ],
                'periodicity' => [
                    'display' => $subscribe->periodicitySetting->value,
                    'order' => $subscribe->periodicitySetting->sort
                ],
                'subscribe_cost_str' => $subscribe->subscribe_cost_str,
                'subscriber_name' => $subscribe->subscriber->name,
                'subscriber_email' => $subscribe->subscriber->email,
                'subscriber_phone_str' => $subscribe->subscriber->phone_str,
            ];
            if (empty($subscribe->pref_acc_id)) {
                $arSubscribe['pref_acc'] = [
                    'display' => '-',
                    'filter' => '-'
                ];
            } else {
                $arSubscribe['pref_acc'] = [
                    'display' => '<div class="rowLimit">'.$subscribe->prefAccSetting->value.'</div>',
                    'filter' => '+'
                ];
            }
            $i = 0;
            foreach ($arPlannedDataLine as $arPlannedSendMonth) {
                $i++;
                $arSubscribe['m' . $i] = $subscribe->estimated_sends_month_days[$arPlannedSendMonth['int']];
            }
            unset($i);
            $arSubscribe['type'] = [
                'display' => $subscribe->subscribeTypeSetting->value,
                'order' => $subscribe->subscribeTypeSetting->sort
            ];
            $arSubscribe['size_top'] = [
                'display' => $subscribe->sizeTopSetting->value,
                'order' => $subscribe->sizeTopSetting->sort
            ];
            $arSubscribe['size_bottom'] = [
                'display' => $subscribe->sizeBottomSetting->value,
                'order' => $subscribe->sizeBottomSetting->sort
            ];
            $arSubscribe['size_height'] = [
                'display' => $subscribe->sizeHeightSetting->value,
                'order' => $subscribe->sizeHeightSetting->sort
            ];
            $arSubscribe['size_foot'] = [
                'display' => $subscribe->sizeFootSetting->value,
                'order' => $subscribe->sizeFootSetting->sort
            ];
            $arSubscribe['delivery_type'] = [
                'display' => '<div class="rowLimit">'.$subscribe->deliverySetting->value.'</div>',
                'order' => $subscribe->deliverySetting->sort
            ];
            $arSubscribe['delivery_addr'] = '<div class="rowLimit">'.$subscribe->delivery_addr.'</div>';
            $arSubscribe['edit_btn'] = '<a class="btn btn-block btn-primary btn-sm" href="'.route('admin.subscribes.edit', $subscribe->id).'"><i class="fa fa-pencil" aria-hidden="true"></i></a>';

            $arSubscribes[] = $arSubscribe;
        }

        $arSubscribes = [
            'data' => $arSubscribes
        ];
        return $arSubscribes;
    }

    // Присваивает каждой подписке сортировку статуса для фильтра в таблице
    private function setStatusSort(&$subscribes){
        $arAllStatuses = Subscribe::getAllStatuses(); // Все статусы подписок
        foreach ($subscribes as &$subscribe) {
            $subscribe->status_sort = $arAllStatuses[$subscribe->status]['sort'];
        }
    }
    // ----------------------------------------------------------------------------------------------

    // Присваивает каждой подписке даты отправки в каждом месяце планируемой отправки.
    // Считаем планируемые даты отправки групповым способом для ускорения загрузки страницы
    private function setEstimatedSendsMonthDays(&$subscribes, $arPlannedDataLine){
        foreach ($subscribes as &$subscribe) {
            // Считаем планируемые даты отправки групповым способом для ускорения загрузки страницы
            $subscribe->estimated_sends_month_days = Subscribe::getEstimatedSendsMonthDays($subscribe,$arPlannedDataLine);
        }
    }
    // ----------------------------------------------------------------------------------------------

}
