<?php

namespace App\Services\Subscribe;

use App\Jobs\OpenExternalUrlJob;
use App\Models\Subscribe;
use App\Models\SubscribeHistory;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class Service
{
    /*
     * Отправляет подписку
     * @param Subscribe $subscribe - подписка
     * @param array $arSendData - данные для отправки
     * @return array - массив результатов
     */
    public function sendSubscribe(Subscribe $subscribe, array $arSendData){
        // Проверяем, активна ли подписка, и планируется ли отправка этой подписки в этом месяце
        $arErrors = [];
        $intResult = 0;
        if ($subscribe->status !== 'Активна') {
            $arErrors[] = $subscribe->subscribeTypeSetting->value . ' (' .
                $subscribe->subscriber->name . ', ' .
                $subscribe->subscriber->email . ', ' .
                $subscribe->subscriber->phone_str .
                ') неактивна, и не может быть отправлена';
        } elseif ($subscribe->next_send_month !== $arSendData['sent_month']) {
            $arErrors[] = $subscribe->subscribeTypeSetting->value . ' (' .
                $subscribe->subscriber->name . ', ' .
                $subscribe->subscriber->email . ', ' .
                $subscribe->subscriber->phone_str .
                ') следующий месяц отправки не '.$arSendData['sent_month'];
        } else {

            // Обновляем подписку
            $sentMonth = Carbon::createFromLocaleIsoFormat('!MMMM YYYY', 'ru', $arSendData['sent_month'], null)->format('m.Y');
            $arUpdateData = [
                'sent_month' => $sentMonth,
                'next_send_month' => $subscribe->after_next_send_month
            ];
            if (!empty($subscribe->sent_month)) $arUpdateData['sent_month'] = $subscribe->sent_month . ',' . $arUpdateData['sent_month'];
            $intResult = (int) $subscribe->update($arUpdateData);

            // Пишем историю
            if (!empty($intResult)) {
                $subscribe = $subscribe->fresh();
                SubscribeHistory::create([
                    'subscribe_id' => $subscribe->id,
                    'date' => $arSendData['sending_date'],
                    'type' => 'Отправка',
                    'info' =>
                        [
                            'Эта отправка' => [
                                'Отправитель' => $arSendData['sender'],
                                'Месяц, за который выполнена отправка' => $arSendData['sent_month'],
                                'Дата отправки' => $arSendData['sending_date'],
                            ],
                            'Параметры подписки' => [
                                'Тип подписки' => $subscribe->subscribeTypeSetting->value,
                                'Статус' => $subscribe->status,
                                'Сумма подписки' => $subscribe->subscribe_cost_str .' р',
                                'Учёт предпочтений' => empty($subscribe->pref_acc_id) ? '-' : $subscribe->prefAccSetting->value,
                                'Исключить' => empty($subscribe->exclude) ? '-' : $subscribe->exclude,
                                'Наш комментарий' => empty($subscribe->comment_manager) ? '-' : $subscribe->comment_manager,
                                'Комментарий подписчика' => empty($subscribe->comment_subscriber) ? '-' : $subscribe->comment_subscriber,
                                'Дата подписки' => $subscribe->subscribe_date_str,
                            ],
                            'Размеры' => [
                                'Верх' => $subscribe->sizeTopSetting->value,
                                'Низ' => $subscribe->sizeBottomSetting->value,
                                'Рост' => $subscribe->sizeHeightSetting->value,
                                'Стопа' => $subscribe->sizeFootSetting->value,
                            ],
                            'Подписчик' => [
                                'Имя' => $subscribe->subscriber->name,
                                'Мейл' => $subscribe->subscriber->email,
                                'Телефон' => $subscribe->subscriber->phone_str,
                            ],
                            'Параметры отправки' => [
                                'Периодичность' => $subscribe->periodicitySetting->value,
                                'Даты отправки' => $subscribe->sendDatesSetting->value,
                                'Следующая отправка' => $subscribe->next_send_month,
                                'Тип доставки' => $subscribe->deliverySetting->value,
                                'Адрес доставки' => $subscribe->delivery_addr,
                            ],
                            'Состав подписки' => $this->getSubscribeConsistForHistory($subscribe->subscribeConsistByMonth($sentMonth))
                        ]
                ]);
            }

            // Отправляем письмо подписчику
            OpenExternalUrlJob::dispatch(env('BB_STORE_URL').'/api/makeSubscriberSubscribeStatusNotification/'.$subscribe->id.'/отправлена');
        }
        return ['intResult' => $intResult, 'arErrors' => $arErrors];
    }
    // --------------------------------------------------------------------------------------------

    /*
     * Обновляет статус подписки (заморожена, отменена и тд)
     * @param Subscribe $subscribe - подписка
     * @param array $data - данные, полученные из формы обновления статуса и валидированные
     * @return int result - результат
     */
    public function updateStatus(Subscribe $subscribe, array $data){
        // Следующая отправка
        if (empty($data['next_send_month'])) $data['next_send_month'] = null;
        // Обновляем подписку
        $arUpdateData = [
            'status' => $data['status'],
            'next_send_month' => (empty($data['next_send_month'])) ? null : $data['next_send_month']
        ];
        if ($subscribe->update($arUpdateData)) {
            $subscribe = $subscribe->fresh();
            // Пишем историю
            SubscribeHistory::create([
                'subscribe_id' => $subscribe->id,
                'date' => date('Y-m-d H:i:s',time()),
                'type' => $data['action_type'],
                'info' =>
                    [
                        'Это действие' => [
                            'Действие' => $data['action_type'],
                            'Выполнил' => $data['user'],
                            'Следующая отправка' => empty($data['next_send_month']) ? 'Отменена' : $data['next_send_month'],
                            'Наш комментарий' => empty($data['comment_manager']) ? '-' : $data['comment_manager'],
                            'Комментарий подписчика' => empty($data['comment_subscriber']) ? '-' : $data['comment_subscriber'],
                        ],
                        'Параметры подписки' => [
                            'Тип подписки' => $subscribe->subscribeTypeSetting->value,
                            'Статус' => $subscribe->status,
                            'Сумма подписки' => $subscribe->subscribe_cost_str .' р',
                            'Учёт предпочтений' => empty($subscribe->pref_acc_id) ? '-' : $subscribe->prefAccSetting->value,
                            'Исключить' => empty($subscribe->exclude) ? '-' : $subscribe->exclude,
                            'Наш комментарий' => empty($subscribe->comment_manager) ? '-' : $subscribe->comment_manager,
                            'Комментарий подписчика' => empty($subscribe->comment_subscriber) ? '-' : $subscribe->comment_subscriber,
                            'Дата подписки' => $subscribe->subscribe_date_str,
                        ],
                        'Размеры' => [
                            'Верх' => $subscribe->sizeTopSetting->value,
                            'Низ' => $subscribe->sizeBottomSetting->value,
                            'Рост' => $subscribe->sizeHeightSetting->value,
                            'Стопа' => $subscribe->sizeFootSetting->value,
                        ],
                        'Подписчик' => [
                            'Имя' => $subscribe->subscriber->name,
                            'Мейл' => $subscribe->subscriber->email,
                            'Телефон' => $subscribe->subscriber->phone_str,
                        ],
                        'Параметры отправки' => [
                            'Периодичность' => $subscribe->periodicitySetting->value,
                            'Даты отправки' => $subscribe->sendDatesSetting->value,
                            'Следующая отправка' => $subscribe->next_send_month,
                            'Тип доставки' => $subscribe->deliverySetting->value,
                            'Адрес доставки' => $subscribe->delivery_addr,
                        ],
                    ]
            ]);
            // Отправляем письмо подписчику
            switch ($data['action_type']){
                case 'Заморозка':
                    $strAction = 'заморожена';
                    break;
                case 'Разморозка':
                    $strAction = 'разморожена';
                    break;
                case 'Отмена':
                    $strAction = 'отменена';
                    break;
                case 'Активация':
                    $strAction = 'активирована';
                    break;
                default:
                    $strAction = '';
                    break;
            }
            if (!empty($strAction)){
                OpenExternalUrlJob::dispatch(env('BB_STORE_URL').'/api/makeSubscriberSubscribeStatusNotification/'.$subscribe->id.'/'.$strAction);
            }
            return true;
        }
        return false;
    }
    // --------------------------------------------------------------------------------------------

    /*
     * Помечает подписку на сборку
     * @param Subscribe $subscribe - подписка
     * @param array $arAssemblyData - данные для сборки
     * @return array - массив результатов
     */
    public function assemblySubscribe(Subscribe $subscribe, array $arAssemblyData){
        // Проверяем, активна ли подписка, и планируется ли отправка этой подписки в этом месяце
        $arErrors = [];
        $intResult = 0;
        $arAssemblyData['assembly_month_int'] = monthStrToInt($arAssemblyData['assembly_month']);
        $dateNow = date('Y-m-d H:i:s', time());
        if ($subscribe->status !== 'Активна') {
            $arErrors[] = $subscribe->subscribeTypeSetting->value . ' (' .
                $subscribe->subscriber->name . ', ' .
                $subscribe->subscriber->email . ', ' .
                $subscribe->subscriber->phone_str .
                ') неактивна, и не может быть помечена на сборку';
        } elseif ($subscribe->next_send_month !== $arAssemblyData['assembly_month']) {
            $arErrors[] = $subscribe->subscribeTypeSetting->value . ' (' .
                $subscribe->subscriber->name . ', ' .
                $subscribe->subscriber->email . ', ' .
                $subscribe->subscriber->phone_str .
                '): следующий месяц отправки не ' . $arAssemblyData['assembly_month'] . ', поэтому не может быть помечена на сборку';
        } elseif ( count($subscribe->subscribeConsistByMonth($arAssemblyData['assembly_month_int'])) ) {
            // Если подписка уже собрана в этом месяце, ничего не меняем
            $intResult = 1;
        } else {
            // Выбираем состав типа подписки
            $subscribeTypeConsist = $subscribe->subscribeTypeSetting->subscribeTypeConsistByMonth($arAssemblyData['assembly_month_int']);
            if (!count($subscribeTypeConsist)) {
                $arErrors[] = 'Не заполнен состав подписок типа '.$subscribe->subscribeTypeSetting->value . ',
                поэтому подписка ' .
                    $subscribe->subscriber->name . ', ' .
                    $subscribe->subscriber->email . ', ' .
                    $subscribe->subscriber->phone_str .
                    ' не может быть помечена на сборку';
            } else {
                $arConsist = [];
                foreach($subscribeTypeConsist as $subscribeTypeProduct) {
                    $arConsist[] = [
                        'product_id' => $subscribeTypeProduct->id,
                        'qnt' => $subscribeTypeProduct->pivot->qnt,
                        'sort' => $subscribeTypeProduct->pivot->sort,
                        'month' => $arAssemblyData['assembly_month_int'],
                        'subscribe_id' => $subscribe->id,
                        'added' => $dateNow
                    ];
                }
                $subscribe->subscribeConsist()->attach($arConsist);
                $intResult = 1;
            }

            // Пишем историю
            if (!empty($intResult)) {
                $subscribe = $subscribe->fresh();
                SubscribeHistory::create([
                    'subscribe_id' => $subscribe->id,
                    'date' => $dateNow,
                    'type' => 'Сборка',
                    'info' =>
                        [
                            'Эта сборка' => [
                                'Отправил на сборку' => auth()->user()->name,
                                'Месяц, за который выполнена сборка' => $arAssemblyData['assembly_month'],
                                'Дата сборки' => date('d.m.Y H:i', time()),
                            ],
                            'Параметры подписки' => [
                                'Тип подписки' => $subscribe->subscribeTypeSetting->value,
                                'Статус' => $subscribe->status,
                                'Сумма подписки' => $subscribe->subscribe_cost_str .' р',
                                'Учёт предпочтений' => empty($subscribe->pref_acc_id) ? '-' : $subscribe->prefAccSetting->value,
                                'Исключить' => empty($subscribe->exclude) ? '-' : $subscribe->exclude,
                                'Наш комментарий' => empty($subscribe->comment_manager) ? '-' : $subscribe->comment_manager,
                                'Комментарий подписчика' => empty($subscribe->comment_subscriber) ? '-' : $subscribe->comment_subscriber,
                                'Дата подписки' => $subscribe->subscribe_date_str,
                            ],
                            'Размеры' => [
                                'Верх' => $subscribe->sizeTopSetting->value,
                                'Низ' => $subscribe->sizeBottomSetting->value,
                                'Рост' => $subscribe->sizeHeightSetting->value,
                                'Стопа' => $subscribe->sizeFootSetting->value,
                            ],
                            'Подписчик' => [
                                'Имя' => $subscribe->subscriber->name,
                                'Мейл' => $subscribe->subscriber->email,
                                'Телефон' => $subscribe->subscriber->phone_str,
                            ],
                            'Параметры отправки' => [
                                'Периодичность' => $subscribe->periodicitySetting->value,
                                'Даты отправки' => $subscribe->sendDatesSetting->value,
                                'Следующая отправка' => $subscribe->next_send_month,
                                'Тип доставки' => $subscribe->deliverySetting->value,
                                'Адрес доставки' => $subscribe->delivery_addr,
                            ],
                            'Состав подписки' => $this->getSubscribeConsistForHistory($subscribe->subscribeConsistByMonth($arAssemblyData['assembly_month_int']))
                        ]
                ]);
            }

        }
        return ['intResult' => $intResult, 'arErrors' => $arErrors];
    }
    // --------------------------------------------------------------------------------------------

    /*
     * Обновляет состав подписки
     * @param Subscribe $subscribe - подписка
     * @param array $arAssemblyData - данные для сборки
     * @return array - массив результатов
     */
    public function updateSubscribeConsist(Subscribe $subscribe, string $month, array $data){
        $arConsist = [];
        $dateNow = date('Y-m-d H:i:s', time());
        foreach ($data['product_id'] as $sort => $product_id) {
            $arConsist[] = [
                'product_id' => $product_id,
                'qnt' => $data['qnt'][$sort],
                'sort' => $sort,
                'month' => $month,
                'subscribe_id' => $subscribe->id,
                'added' => $dateNow
            ];
        }
        $arNotification = [
            'class' => 'success',
            'message' => 'Данные успешно обновлены'
        ];
        DB::beginTransaction();
        try {
            $subscribe->subscribeConsist()->detach($subscribe->subscribeConsistByMonth($month));
            $subscribe->subscribeConsist()->attach($arConsist);
            // Пишем историю
            $subscribe = $subscribe->fresh();
            SubscribeHistory::create([
                'subscribe_id' => $subscribe->id,
                'date' => $dateNow,
                'type' => 'Изменение сборки',
                'info' =>
                    [
                        'Эта сборка' => [
                            'Отправил на сборку' => auth()->user()->name,
                            'Месяц, за который выполнена сборка' => monthIntToStr($month),
                            'Дата сборки' => date('d.m.Y H:i', time()),
                        ],
                        'Параметры подписки' => [
                            'Тип подписки' => $subscribe->subscribeTypeSetting->value,
                            'Статус' => $subscribe->status,
                            'Сумма подписки' => $subscribe->subscribe_cost_str .' р',
                            'Учёт предпочтений' => empty($subscribe->pref_acc_id) ? '-' : $subscribe->prefAccSetting->value,
                            'Исключить' => empty($subscribe->exclude) ? '-' : $subscribe->exclude,
                            'Наш комментарий' => empty($subscribe->comment_manager) ? '-' : $subscribe->comment_manager,
                            'Комментарий подписчика' => empty($subscribe->comment_subscriber) ? '-' : $subscribe->comment_subscriber,
                            'Дата подписки' => $subscribe->subscribe_date_str,
                        ],
                        'Размеры' => [
                            'Верх' => $subscribe->sizeTopSetting->value,
                            'Низ' => $subscribe->sizeBottomSetting->value,
                            'Рост' => $subscribe->sizeHeightSetting->value,
                            'Стопа' => $subscribe->sizeFootSetting->value,
                        ],
                        'Подписчик' => [
                            'Имя' => $subscribe->subscriber->name,
                            'Мейл' => $subscribe->subscriber->email,
                            'Телефон' => $subscribe->subscriber->phone_str,
                        ],
                        'Параметры отправки' => [
                            'Периодичность' => $subscribe->periodicitySetting->value,
                            'Даты отправки' => $subscribe->sendDatesSetting->value,
                            'Следующая отправка' => $subscribe->next_send_month,
                            'Тип доставки' => $subscribe->deliverySetting->value,
                            'Адрес доставки' => $subscribe->delivery_addr,
                        ],
                        'Состав подписки' => $this->getSubscribeConsistForHistory($subscribe->subscribeConsistByMonth($month))
                    ]
            ]);

        } catch (\Exception $exception) {
            DB::rollBack();
            $arNotification = [
                'class' => 'danger',
                'message' => 'При обновлении данных произошла ошибка: '.$exception->getMessage()
            ];
        }
        DB::commit();
        return $arNotification;
    }
    // --------------------------------------------------------------------------------------------

    // Состав подписки для истории
    function getSubscribeConsistForHistory($subscribeConsist){
        $arConsistForHistory = [];
        foreach ($subscribeConsist as $subscribeConsistProduct) {
            $arConsistForHistory[$subscribeConsistProduct->article.', '.$subscribeConsistProduct->name] = $subscribeConsistProduct->pivot->qnt.'шт';
        }
        return count($arConsistForHistory) ? $arConsistForHistory : 'не указан';
    }
}
