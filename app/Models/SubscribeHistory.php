<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;


class SubscribeHistory extends Model
{
    use HasFactory;
    protected $connection = 'mysql_no_prefix';
    protected $table = 'loncq_subscribe_history';
    protected $guarded = false;
    public $timestamps = false;
    protected $with = [];
    protected $appends = [
        'date_diff', // Дата в формате "1 час назад"
        'history_header', // Заголовок в блоке истории
        'history_icon', // Иконка в блоке истории
    ];

    // Мутатор для даты события
    protected function Date(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => (empty($value)) ? '' : Carbon::parse($value)->format('d.m.Y H:i'),
            set: fn ($value) => (empty($value)) ? NULL : Carbon::parse($value)->format('Y-m-d H:i:s'),
        );
    }

    // Мутатор для подробностей
    protected function Info(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => json_decode($value),
            set: fn ($value) => json_encode($value),
        );
    }

    // Полная стоимость подписки (с доставкой)
    public function getDateDiffAttribute() {
        return Carbon::parse($this->date)->diffForHumans();
    }

    // Полная стоимость подписки (с доставкой)
    public function getHistoryHeaderAttribute() {
        $history_header = $this->type;
        if ($this->type === 'Отправка' && !empty($this->info->{'Эта отправка'}->{'Месяц, за который выполнена отправка'})) {
            $history_header .= ' за ' . $this->info->{'Эта отправка'}->{'Месяц, за который выполнена отправка'};
        } elseif (in_array($this->type,['Сборка','Изменение сборки']) && !empty($this->info->{'Эта сборка'}->{'Месяц, за который выполнена сборка'})) {
            $history_header .= ' за ' . $this->info->{'Эта сборка'}->{'Месяц, за который выполнена сборка'};
        }
        return $history_header;
    }

    // Полная стоимость подписки (с доставкой)
    public function getHistoryIconAttribute() {
        switch ($this->type) {
            case 'Отправка':
                return 'fa-envelope bg-blue';
                break;
            case 'Заморозка':
                return 'fa-ban bg-gray';
                break;
            case 'Разморозка':
                return 'fa-check bg-green';
                break;
            case 'Отмена':
                return 'fa-times bg-gray';
                break;
            case 'Активация':
                return 'fa-check bg-green';
                break;
            case 'Сборка':
            case 'Изменение сборки':
                return 'fa-archive bg-blue';
                break;
            default:
                return 'fa-clock-o bg-gray';
                break;
        }
    }

}
