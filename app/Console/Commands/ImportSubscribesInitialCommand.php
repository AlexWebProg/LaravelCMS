<?php

namespace App\Console\Commands;

use App\Imports\TestSubscribesExcelImport;
use App\Models\Subscribe;
use App\Models\Subscriber;
use App\Models\SubscribeSettings;
use Illuminate\Support\Facades\DB;

use Illuminate\Console\Command;

class ImportSubscribesInitialCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:subscribes-initial';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Initial subscribes import from excel file';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $arSubscribers = []; // Подписчики
        $arSubscribes = []; // Подписки

        $arSettings = [];
        $arCosts = [];
        $subscribeSettings = SubscribeSettings::where('is_active', 1)
            ->orderBy('type')
            ->orderBy('sort')
            ->get();
        foreach ($subscribeSettings as $subscribeSetting){
            $arSettings[$subscribeSetting->type][$subscribeSetting->value] = $subscribeSetting->id;
            $arCosts[$subscribeSetting->id] = $subscribeSetting->cost;
        }
        // Лист "Активные"
        $arData = (new TestSubscribesExcelImport)->toArray('test/subscribes.xlsx')[5];
        unset($arData[0]);
        if (!empty($arData) and count($arData)) {
            foreach ($arData as &$arRow) {
                $arRow['status'] = 'Активна';
            }
        }

        // Лист "Отписались"
        $arReject = (new TestSubscribesExcelImport)->toArray('test/subscribes.xlsx')[6];
        unset($arReject[0]);
        if (!empty($arReject) and count($arReject)) {
            foreach ($arReject as $arRejectRow) {
                if (empty($arRejectRow[28])) $arRejectRow[28] = 'Доставка на ПВЗ Боксберри';
                if ($arRejectRow[28] == 'Доставка на ПВЗ') $arRejectRow[28] = 'Доставка на ПВЗ Боксберри';
                $arRejectRow['status'] = 'Отменена';
                $arData[] = $arRejectRow;
            }
        }
        unset($arReject);
        // Лист "Заморозка"
        $arFreeze = (new TestSubscribesExcelImport)->toArray('test/subscribes.xlsx')[7];
        unset($arFreeze[0]);
        if (!empty($arFreeze) and count($arFreeze)) {
            foreach ($arFreeze as $arFreezeRow) {
                if (!empty($arFreezeRow[1])) {
                    if (empty($arFreezeRow[28])) $arFreezeRow[28] = 'Доставка на ПВЗ Боксберри';
                    if ($arFreezeRow[28] == 'Доставка на ПВЗ') $arFreezeRow[28] = 'Доставка на ПВЗ Боксберри';
                    $arFreezeRow['status'] = 'Заморожена';
                    $arData[] = $arFreezeRow;
                }
            }
        }
        unset($arFreeze);

        if (!empty($arData) and count($arData)) {
            foreach ($arData as $arRow) {
                if (!empty(trim($arRow[2]))) {
                    // Подписчик
                    $arSubscribers[] = [
                        'name' => trim($arRow[4]),
                        'phone' => preg_replace('/[^[:digit:]]/', '', $arRow[6]),
                        'email' => trim($arRow[5]),
                        'group_id' => 4,
                        'subscriber' => 1
                    ];
                    // Подписка
                    $strNextSendMonth = NULL;
                    $strMonthSendDates = NULL;
                    if ($arRow['status'] == 'Активна') {
                        if ($arRow[19] != '-') {
                            $strNextSendMonth = 'сентябрь 2022';
                            $strMonthSendDates = $arRow[19];
                        } elseif ($arRow[20] != '-') {
                            $strNextSendMonth = 'октябрь 2022';
                            $strMonthSendDates = $arRow[20];
                        } elseif ($arRow[21] != '-') {
                            $strNextSendMonth = 'ноябрь 2022';
                            $strMonthSendDates = $arRow[21];
                        } elseif ($arRow[22] != '-') {
                            $strNextSendMonth = 'декабрь 2022';
                            $strMonthSendDates = $arRow[22];
                        }
                    }
                    $arSentMonth = [];
                    if ($arRow[9] != '-') $arSentMonth[] = '11.2021';
                    if ($arRow[10] != '-') $arSentMonth[] = '12.2021';
                    if ($arRow[11] != '-') $arSentMonth[] = '01.2022';
                    if ($arRow[12] != '-') $arSentMonth[] = '02.2022';
                    if ($arRow[13] != '-') $arSentMonth[] = '03.2022';
                    if ($arRow[14] != '-') $arSentMonth[] = '04.2022';
                    if ($arRow[15] != '-') $arSentMonth[] = '05.2022';
                    if ($arRow[16] != '-') $arSentMonth[] = '06.2022';
                    if ($arRow[17] != '-') $arSentMonth[] = '07.2022';
                    if ($arRow[18] != '-') $arSentMonth[] = '08.2022';
                    $arSubscribe = [
                        'subscribe_date' => $arRow[1],
                        'periodicity_id' => $arSettings['periodicity'][trim($arRow[2])],
                        'delivery_addr' => trim($arRow[29]),
                        'delivery_type_id' => $arSettings['delivery_type'][trim($arRow[28])],
                        'size_top_id' => $arSettings['size_top'][trim($arRow[24])],
                        'size_bottom_id' => $arSettings['size_bottom'][trim($arRow[25])],
                        'size_height_id' => $arSettings['size_height'][trim($arRow[26])],
                        'size_foot_id' => $arSettings['size_foot'][trim($arRow[27])],
                        'subscribe_type_id' => $arSettings['subscribe_type'][trim($arRow[23])],
                        'status' => $arRow['status'],
                        'send_dates_id' => ($strMonthSendDates == 27) ? $arSettings['send_dates']['с 27 по 3'] : $arSettings['send_dates']['с 13 по 20'],
                        'exclude' => (trim($arRow[7]) == '-') ? NULL : trim($arRow[7]),
                        'comment_manager' => (trim($arRow[8]) == '') ? NULL : trim($arRow[8]),
                        'comment_subscriber' => (trim($arRow[30]) == '') ? NULL : trim($arRow[30]),
                        'next_send_month' => $strNextSendMonth,
                        'sent_month' => implode(',', $arSentMonth),
                        'email' => trim($arRow[5]),
                        'phone' => preg_replace('/[^[:digit:]]/', '', $arRow[6]),
                    ];
                    // Учёт предпочтений
                    if (!empty($arRow[0]) && $arRow[0] == '1') {
                        $pieces = explode(' ', trim($arRow[23]));
                        $last_word = array_pop($pieces);
                        $arSubscribe['pref_acc_id'] = $arSettings['pref_acc'][$last_word];
                        unset($last_word, $pieces);
                    }
                    $intAutoPrice = $arCosts[$arSubscribe['delivery_type_id']] + $arCosts[$arSubscribe['subscribe_type_id']];
                    if (!empty($arSubscribe['pref_acc_id'])) {
                        $intAutoPrice += (int) $arCosts[$arSubscribe['pref_acc_id']];
                    }
                    if ($arRow[3] == $intAutoPrice) {
                        $arSubscribe['sum_calc_type'] = 0;
                        $arSubscribe['sum'] = NULL;
                    } else {
                        $arSubscribe['sum_calc_type'] = 1;
                        $arSubscribe['sum'] = $arRow[3];
                    }
                    $arSubscribes[] = $arSubscribe;
                    unset($arSubscribe, $strNextSendMonth, $arSentMonth, $intAutoPrice, $strMonthSendDates);
                }
            }
        }

        // Удаляем повторяющиеся данные
        if (count($arSubscribers)) {
            $arSubscribers = array_map('unserialize', array_unique(array_map('serialize', $arSubscribers)));
        }
        $arErrors = [];

        // Обновляем подписки и подписчиков в БД
        // Начинаем транзакцию
        DB::beginTransaction();
        // Обновляем или создаём подписчиков
        try {
            // Указываем, что никто не подписчик
            DB::connection('mysql_no_prefix')->table('loncq_user')->update(['subscriber' => 0]);
            foreach ($arSubscribers as $arSubscriber) {
                // Если пользователь есть в БД, обновляем его. Если нет - создаём
                $objDBUser = Subscriber::getSubscriber($arSubscriber['email'], $arSubscriber['phone']);
                if (!empty($objDBUser->user_id)) {
                    $objDBUser->update($arSubscriber);
                } else {
                    Subscriber::addSubscriber($arSubscriber);
                }
                unset($objDBUser);
            }
            // Обновляем подписки
            // Удаляем все подписки
            DB::connection('mysql_no_prefix')->table('loncq_user_subscribes')->truncate();
            foreach ($arSubscribes as $arSubscribe) {
                // Заполняем user_id в массиве подписок
                $arSubscribe['user_id'] = Subscriber::getSubscriber($arSubscribe['email'], $arSubscribe['phone'])->user_id;
                if (empty($arSubscribe['user_id'])) {
                    $arErrors[] = 'На сайте не найден подписчик с мейл '.$arSubscribe['email'].' и номером телефона '.$arSubscribe['phone'];
                }
                // Сохраняем подписку в БД
                unset($arSubscribe['phone'],$arSubscribe['email']);
                Subscribe::create($arSubscribe);
            }
        } catch (\Exception $exception) {
            $arErrors[] = $exception->getMessage();
        }

        // Завершаем или откатываем транзакцию
        if (count($arErrors)) {
            DB::rollBack();
        } else {
            DB::commit();
        }

        // Уведомляем пользователя
        if (count($arErrors)) {
            dd(implode(', ',$arErrors));
        } else {
            dd('Подписки и контактные данные подписчиков успешно обновлены');
        }
        return 0;
    }
}
