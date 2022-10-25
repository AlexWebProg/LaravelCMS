<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;


class Subscribe extends Model
{
    use HasFactory;
    protected $connection = 'mysql_no_prefix';
    protected $table = 'loncq_user_subscribes';
    protected $guarded = false;
    public $timestamps = false;
    protected $with = [
        'subscriber', // Подписчик подписки
        'deliverySetting', // Тип доставки
        'periodicitySetting', // Периодичность отправки
        'sendDatesSetting', // Даты отправки
        'sizeBottomSetting', // Размер низ
        'sizeFootSetting', // Размер стопа
        'sizeHeightSetting', // Размер рост
        'sizeTopSetting', // Размер верх
        'subscribeTypeSetting', // Тип подписки
        'prefAccSetting', // Учёт предпочтений
        'subscribeConsist', //  Состав подписки
    ];
    protected $appends = [
        'subscribe_cost', // Полная стоимость подписки (с доставкой)
        'subscribe_cost_str', // Полная стоимость подписки (с доставкой) в текстовом формате
        'subscribe_date_str', // Дата подписки в текстовом формате (для таблицы)
        'estimated_sends', // Планируемые отправки (ближайшие 5 отправок)
        'after_next_send_month', // После-следующая отправка
    ];

    // Автоматические действия для модели
    protected static function booted()
    {
        static::saved(function ($subscribe) {
            // После сохранения подписки пересчитываем дату отправки для подписчика и обновляем её в подписке
            $subscribe = $subscribe->fresh();
            $subscribe->send_date = $subscribe->next_send_month;
            $nextSendMonthOriginalDate = $subscribe->getAttributes()['next_send_month'];
            if (!empty($nextSendMonthOriginalDate)) {
                $nextSendMonthCarbonlDate = Carbon::parse($nextSendMonthOriginalDate);
                preg_match_all('!\d+!', $subscribe->sendDatesSetting->value, $arSendDates);
                if (!empty($arSendDates[0]) and is_array($arSendDates[0]) and count($arSendDates[0])) {
                    if (count($arSendDates[0]) == 1) {
                        $strDate = $arSendDates[0][0].'.'.$nextSendMonthCarbonlDate->format('m.Y');
                        $carbonDate = Carbon::parse($strDate);
                        $subscribe->send_date = $carbonDate->isoFormat('DD MMMM');
                    } elseif (count($arSendDates[0]) == 2) {
                        if ($arSendDates[0][0] === $arSendDates[0][1]) {
                            $strDate = $arSendDates[0][0].'.'.$nextSendMonthCarbonlDate->format('m.Y');
                            $carbonDate = Carbon::parse($strDate);
                            $subscribe->send_date = $carbonDate->isoFormat('DD MMMM');
                        } else if ($arSendDates[0][0] < $arSendDates[0][1]) {
                            $strEndDate = $arSendDates[0][1].'.'.$nextSendMonthCarbonlDate->format('m.Y');
                            $carbonDate = Carbon::parse($strEndDate);
                            $subscribe->send_date = 'с ' . $arSendDates[0][0] . ' по ' . $carbonDate->isoFormat('DD MMMM');
                        } else {
                            $strStartDate = $arSendDates[0][0].'.'.$nextSendMonthCarbonlDate->format('m.Y');
                            $carbonStartDate = Carbon::parse($strStartDate);
                            $nextSendMonthCarbonlDatePlusOne = $nextSendMonthCarbonlDate->addMonth();
                            $strEndDate = $arSendDates[0][1].'.'.$nextSendMonthCarbonlDatePlusOne->format('m.Y');
                            $carbonEndDate = Carbon::parse($strEndDate);
                            $subscribe->send_date = 'с ' . $carbonStartDate->isoFormat('DD MMMM') . ' по ' . $carbonEndDate->isoFormat('DD MMMM');
                        }
                    } else {
                        $subscribe->send_date = $subscribe->next_send_month . ': ' . $subscribe->sendDatesSetting->value;
                    }
                }
            } else {
                $subscribe->send_date = 'нет';
            }
            $subscribe->updateQuietly(['send_date' => $subscribe->send_date]);
        });
    }

    // Подписчик подписки
    public function subscriber()
    {
        return $this->belongsTo(Subscriber::class, 'user_id', 'user_id');
    }

    // Тип доставки
    public function deliverySetting()
    {
        return $this->belongsTo(SubscribeSettings::class, 'delivery_type_id');
    }

    // Периодичность отправки
    public function periodicitySetting()
    {
        return $this->belongsTo(SubscribeSettings::class, 'periodicity_id');
    }

    // Даты отправки
    public function sendDatesSetting()
    {
        return $this->belongsTo(SubscribeSettings::class, 'send_dates_id');
    }

    // Размер низ
    public function sizeBottomSetting()
    {
        return $this->belongsTo(SubscribeSettings::class, 'size_bottom_id');
    }

    // Размер стопа
    public function sizeFootSetting()
    {
        return $this->belongsTo(SubscribeSettings::class, 'size_foot_id');
    }

    // Размер рост
    public function sizeHeightSetting()
    {
        return $this->belongsTo(SubscribeSettings::class, 'size_height_id');
    }

    // Размер верх
    public function sizeTopSetting()
    {
        return $this->belongsTo(SubscribeSettings::class, 'size_top_id');
    }

    // Тип подписки
    public function subscribeTypeSetting()
    {
        return $this->belongsTo(SubscribeSettings::class, 'subscribe_type_id');
    }

    // Учёт предпочтений
    public function prefAccSetting()
    {
        return $this->belongsTo(SubscribeSettings::class, 'pref_acc_id');
    }

    // История подписки
    public function history()
    {
        return $this->hasMany(SubscribeHistory::class, 'subscribe_id')->orderBy('id','DESC');
    }

    // Мутатор для даты подписки
    protected function subscribeDate(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => (empty($value)) ? '' : Carbon::parse($value)->format('d.m.Y H:i'),
            set: fn ($value) => (empty($value)) ? NULL : Carbon::parse($value)->format('Y-m-d H:i:s'),
        );
    }

    // Сумма подписки, введённая вручную
    protected function sum(): Attribute
    {
        return Attribute::make(
            set: fn ($value) => (empty($value)) ? NULL : floatval(str_replace([' ',','], ['','.'], $value)),
        );
    }

    // Следующая отправка (месяц и год)
    protected function nextSendMonth(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => (empty($value)) ? '' : Carbon::parse($value)->isoFormat('MMMM YYYY'),
            set: fn ($value) => (empty($value)) ? NULL : Carbon::createFromLocaleIsoFormat('!MMMM YYYY', 'ru', $value, null)->format('Y-m-d'),
        );
    }

    // Полная стоимость подписки (с доставкой)
    public function getSubscribeCostAttribute() {
        if ($this->sum_calc_type === 1) {
            return $this->sum;
        } else {
            $intSum = (int) $this->deliverySetting->cost + (int) $this->subscribeTypeSetting->cost;
            if (!empty($this->pref_acc_id)) {
                $intSum += (int) $this->prefAccSetting->cost;
            }
            return $intSum;
        }
    }

    // Полная стоимость подписки в текстовом формате
    public function getSubscribeCostStrAttribute() {
        return number_format($this->subscribe_cost,2,'.',' ');
    }

    // Дата подписки в текстовом формате
    public function getSubscribeDateStrAttribute() {
        return (empty($this->subscribe_date)) ? '-' : $this->subscribe_date;
    }

    // Планируемые отправки (ближайшие 5 отправок)
    public function getEstimatedSendsAttribute()
    {
        $arEstimatedSends = [];
        if (!empty($this->getAttributes()['next_send_month'])) {
            // Ближайшая отправка
            $nextSend = Carbon::parse($this->getAttributes()['next_send_month']);
            // Периодичность отправки
            $intPeriodicity = preg_replace( '/[^0-9]/', '',$this->periodicitySetting->value);
            if (empty($intPeriodicity)) $intPeriodicity = 1;
            $arEstimatedSends[$nextSend->format('m.Y')] = [
                'int' => $nextSend->format('m.Y'),
                'str' => $nextSend->isoFormat('MMMM YYYY')

            ];
            for ($i=1; $i <= 4; $i++) {
                $nextSend->addMonths($intPeriodicity);
                $arEstimatedSends[$nextSend->format('m.Y')] = [
                    'int' => $nextSend->format('m.Y'),
                    'str' => $nextSend->isoFormat('MMMM YYYY')

                ];
            }
        }
        return $arEstimatedSends;
    }

    // После-следующая отправка
    public function getAfterNextSendMonthAttribute() {
        $sendings = 0;
        foreach ($this->estimated_sends as $arEstimatedSend) {
            $sendings++;
            if ($sendings === 2) {
                return $arEstimatedSend['str'];
            }
        }
        return '';
    }

    // Планируемые даты отправки в ближайшие месяцы - функция для нахождения (можно использовать при групповой обработке)
    // $subscribe - подписка
    // $arPlannedDataLine - месяцы на ближайший рабочий срок (self::getPlannedDataLine)
    public static function getEstimatedSendsMonthDays(Subscribe $subscribe, array $arPlannedDataLine) {
        $arEstimatedSendMonthDays = [];
        $arSendMonth = array_flip(explode(',',$subscribe->sent_month ?? ''));
        $arEstimatedSends = $subscribe->estimated_sends;
        $sendDates = $subscribe->sendDatesSetting->value;
        foreach($arPlannedDataLine as $arPlannedSendMonth) {
            if (isset($arSendMonth[$arPlannedSendMonth['int']])) {
                $arEstimatedSendMonthDays[$arPlannedSendMonth['int']] = '+';
            } elseif (!empty($arEstimatedSends[$arPlannedSendMonth['int']])) {
                $arEstimatedSendMonthDays[$arPlannedSendMonth['int']] = $sendDates;
            } else {
                $arEstimatedSendMonthDays[$arPlannedSendMonth['int']] = '-';
            }
        }
        return $arEstimatedSendMonthDays;
    }

    // Все возможные статусы подписок
    public static function getAllStatuses(){
        return [
            'Активна' => [
                'sort' => 1,
                'value' => 'Активна'
            ],
            'Заморожена' => [
                'sort' => 2,
                'value' => 'Заморожена'
            ],
            'Отменена' => [
                'sort' => 3,
                'value' => 'Отменена'
            ],
        ];
    }

    // Получает месяцы на ближайший рабочий срок (ближайшие 6 месяцев)
    public static function getPlannedDataLine(){
        $arPlannedDataLine = [];
        $strCurrentMonth = '01.'.Carbon::now()->format('m.Y');
        $lastMonthDateTime = Carbon::parse($strCurrentMonth)->subMonth();
        $arPlannedDataLine[] = [
            'int' => $lastMonthDateTime->format('m.Y'),
            'str' => $lastMonthDateTime->isoFormat('MMMM YYYY')

        ];
        $carbonCurrentMonth = Carbon::parse($strCurrentMonth);
        $arPlannedDataLine[] = [
            'int' => $carbonCurrentMonth->format('m.Y'),
            'str' => $carbonCurrentMonth->isoFormat('MMMM YYYY')

        ];
        for ($i=1; $i <= 5; $i++) {
            $nextDateTime = Carbon::parse($strCurrentMonth)->addMonths($i);
            $arPlannedDataLine[] = [
                'int' => $nextDateTime->format('m.Y'),
                'str' => $nextDateTime->isoFormat('MMMM YYYY')

            ];
        }
        return $arPlannedDataLine;
    }

    // Состав подписки
    public function subscribeConsist() {
        return $this->belongsToMany(
            SubscribeProduct::class,
            'loncq_subscribe_consist',
            'subscribe_id',
            'product_id')
            ->withPivot('qnt', 'month')
            ->orderByPivot('sort');
    }

    // Состав подписки по месяцу
    public function subscribeConsistByMonth($month) {
        return $this->subscribeConsist->filter(function ($subscribeProduct) use ($month) {
            return $subscribeProduct->pivot->month === $month;
        })->values();
    }

}
