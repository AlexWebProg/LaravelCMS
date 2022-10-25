<?php

namespace App\Http\Controllers\Admin\Subscribe;

use App\Models\Subscribe;
use Illuminate\Http\Request;

use App\Exports\SubscribesExcelExport;
use Maatwebsite\Excel\Facades\Excel;

class ExcelExportController extends BaseController
{
    // Экспорт подписок в excel
    public function __invoke(Request $request)
    {
        // Массив выбранных ID подписок
        $arIDs = $request->subscribe_id;
        // Выборка отмеченных подписок
        $subscribes = Subscribe::whereIn('id', $arIDs)
            ->get();
        // Сортируем выборку как в таблице (в порядке следования полученных ID)
        $subscribes = $subscribes->sortBy(function ($subscribe) use ($arIDs) {
            return array_search($subscribe->id, $arIDs);
        });
        $arPlannedDataLine = Subscribe::getPlannedDataLine();
        // Заголовки столбцов
        $arHeadings = [
            'Дата подписки',
            'Статус',
            'Периодичность',
            'Сумма подписки',
            'Имя',
            'Мейл',
            'Телефон',
            'Учёт предпочтений',
            'Исключить',
            'Наш комментарий'
        ];
        foreach($arPlannedDataLine as $arPlannedSendMonth) {
            $arHeadings[] = $arPlannedSendMonth['str'];
        }
        $arHeadings[] = 'Подписка';
        $arHeadings[] = 'Верх';
        $arHeadings[] = 'Низ';
        $arHeadings[] = 'Рост';
        $arHeadings[] = 'Стопа';
        $arHeadings[] = 'Тип доставки';
        $arHeadings[] = 'Адрес доставки';
        // Данные таблицы
        $arData = [];
        foreach ($subscribes as $subscribe) {
            // Считаем планируемые даты отправки групповым способом для ускорения загрузки страницы
            $subscribe->estimated_sends_month_days = Subscribe::getEstimatedSendsMonthDays($subscribe,$arPlannedDataLine);
            $arRow = [
                $subscribe->subscribe_date_str,
                $subscribe->status,
                $subscribe->periodicitySetting->value,
                $subscribe->subscribe_cost_str,
                $subscribe->subscriber->name,
                $subscribe->subscriber->email,
                $subscribe->subscriber->phone_str,
                empty($subscribe->pref_acc_id) ? '-' : $subscribe->prefAccSetting->value,
                empty($subscribe->exclude) ? '-' : $subscribe->exclude,
                empty($subscribe->comment_manager) ? '-' : $subscribe->comment_manager
            ];
            foreach($arPlannedDataLine as $arPlannedSendMonth) {
                $arRow[] = $subscribe->estimated_sends_month_days[$arPlannedSendMonth['int']];
            }
            $arRow[] = $subscribe->subscribeTypeSetting->value;
            $arRow[] = $subscribe->sizeTopSetting->value;
            $arRow[] = $subscribe->sizeBottomSetting->value;
            $arRow[] = $subscribe->sizeHeightSetting->value;
            $arRow[] = $subscribe->sizeFootSetting->value;
            $arRow[] = $subscribe->deliverySetting->value;
            $arRow[] = $subscribe->delivery_addr;
            $arData[] = $arRow;
        }
        // Экспорт в excel и отдача файла в браузер
        $export = new SubscribesExcelExport($arHeadings,$arData);
        return Excel::download($export, 'BLACKBASE Subscribes '.date('Y-m-d').'.xlsx');
    }
}
