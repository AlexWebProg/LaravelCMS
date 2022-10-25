<?php

namespace App\Http\Controllers\Admin\SubscribeConsist;

use App\Models\Subscribe;
use App\Models\SubscribeConsist;

class IndexJSONController extends BaseController
{

    public function __invoke($month)
    {
        $subscribeIDs = SubscribeConsist::where('month', $month)
            ->select('subscribe_id','added')
            ->distinct()
            ->orderBy('added','DESC')
            ->get();

        $arData = [];
        foreach($subscribeIDs as $subscribeID) {
            $arRow = [
                'checkbox' => '<input type="checkbox" class="subscribe_checkbox" value="'.$subscribeID->subscribe->id.'">',
                'type' => [
                    'display' => $subscribeID->subscribe->subscribeTypeSetting->value,
                    'order' => $subscribeID->subscribe->subscribeTypeSetting->sort
                ],
                'added' => [
                    'display' => $subscribeID->added_str,
                    'order' => $subscribeID->added
                ],
                'subscribe_cost_str' => $subscribeID->subscribe->subscribe_cost_str,
                'subscriber' => '<ul class="list-unstyled mb-0">
                                    <li>'.$subscribeID->subscribe->subscriber->name.'</li>
                                    <li>'.$subscribeID->subscribe->subscriber->email.'</li>
                                    <li>'.$subscribeID->subscribe->subscriber->phone_str.'</li>
                                </ul>'
            ];
            if (empty($subscribeID->subscribe->pref_acc_id)) {
                $arRow['pref_acc'] = [
                    'display' => '-',
                    'filter' => '-'
                ];
            } else {
                $arRow['pref_acc'] = [
                    'display' => $subscribeID->subscribe->prefAccSetting->value,
                    'filter' => '+'
                ];
            }
            $arRow['sizes'] = '<ul class="list-unstyled mb-0">
                                    <li class="text-nowrap">Верх: '. $subscribeID->subscribe->sizeTopSetting->value .'</li>
                                    <li class="text-nowrap">Низ: '. $subscribeID->subscribe->sizeBottomSetting->value .'</li>
                                    <li class="text-nowrap">Рост: '. $subscribeID->subscribe->sizeHeightSetting->value .'</li>
                                    <li class="text-nowrap">Стопа: '. $subscribeID->subscribe->sizeFootSetting->value .'</li>
                                </ul>';
            $strConsist = '<ul class="list-unstyled mb-0">';
            foreach($subscribeID->subscribe->subscribeConsistByMonth($month) as $subscribeTypeProduct) {
                $strConsist .= '<li>'. $subscribeTypeProduct->article .', '. $subscribeTypeProduct->name .': '. $subscribeTypeProduct->pivot->qnt .'шт</li>';
            }
            $strConsist .= '</ul>';
            $arRow['consist'] = $strConsist;
            unset($strConsist);
            $arRow['send_info'] = Subscribe::getEstimatedSendsMonthDays($subscribeID->subscribe,[['int' => $month]])[$month];
            $arRow['edit_btn'] = '<a class="btn btn-block btn-primary btn-sm" href="'.route('admin.subscribeConsist.edit', [$subscribeID->subscribe->id, $month]).'"><i class="fa fa-pencil" aria-hidden="true"></i></a>';
            $arData[] = $arRow;
        }
        $arData = [
            'data' => $arData
        ];
        return $arData;
    }

}
