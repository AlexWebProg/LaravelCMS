<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class SubscribeSettings extends Model
{
    use HasFactory;
    protected $connection = 'mysql_no_prefix';
    protected $table = 'loncq_subscribe_settings';
    protected $guarded = false;
    public $timestamps = false;
    protected static $arTypes = [
        'periodicity' =>
            [
                'code' => 'periodicity',
                'short_name' => 'Периодичность',
                'full_name' => 'Периодичность отправки',
                'icon' => 'fa fa-calendar-check-o'
            ],
        'send_dates' =>
            [
                'code' => 'send_dates',
                'short_name' => 'Даты отправки',
                'full_name' => 'Даты отправки',
                'icon' => 'fa fa-calendar-check-o'
            ],
        'subscribe_type' =>
            [
                'code' => 'subscribe_type',
                'short_name' => 'Типы подписки',
                'full_name' => 'Типы подписки',
                'icon' => 'fa fa-th-list'
            ],
        'size_top' =>
            [
                'code' => 'size_top',
                'short_name' => 'Размеры верх',
                'full_name' => 'Размеры верх',
                'icon' => 'fa fa-hand-o-up'
            ],
        'size_bottom' =>
            [
                'code' => 'size_bottom',
                'short_name' => 'Размеры низ',
                'full_name' => 'Размеры низ',
                'icon' => 'fa fa-hand-o-down'
            ],
        'size_height' =>
            [
                'code' => 'size_height',
                'short_name' => 'Размеры рост',
                'full_name' => 'Размеры рост',
                'icon' => 'fa fa-arrows-v'
            ],
        'size_foot' =>
            [
                'code' => 'size_foot',
                'short_name' => 'Размеры стопы',
                'full_name' => 'Размеры стопы',
                'icon' => 'fa fa-compress'
            ],
        'delivery_type' =>
            [
                'code' => 'delivery_type',
                'short_name' => 'Типы доставки',
                'full_name' => 'Типы доставки',
                'icon' => 'fa fa-truck'
            ],
        'pref_acc' =>
            [
                'code' => 'pref_acc',
                'short_name' => 'Учёт предпочтений',
                'full_name' => 'Учёт предпочтений',
                'icon' => 'fa fa-star-o'
            ],
    ];

    // Даты отправки
    public function subscibesBySendDatesSetting()
    {
        return $this->hasMany(Subscribe::class, 'send_dates_id');
    }

    // Возвращает типы параметров
    public static function getTypes()
    {
        return self::$arTypes;
    }

    // Состав типа подписки
    public function subscribeTypeConsist() {
        return $this->belongsToMany(
            SubscribeProduct::class,
            'loncq_subscribe_type_consist',
            'subscribe_type_id',
            'product_id')
            ->withPivot('qnt', 'month', 'sort')
            ->orderByPivot('sort');
    }

    // Состав типа подписки по месяцу
    public function subscribeTypeConsistByMonth($month) {
        return $this->subscribeTypeConsist->filter(function ($subscribeTypeProduct) use ($month) {
            return $subscribeTypeProduct->pivot->month === $month;
        })->values();
        /*return $this->belongsToMany(
            SubscribeProduct::class,
            'loncq_subscribe_type_consist',
            'subscribe_type_id',
            'product_id')
            ->withPivot('qnt', 'month')
            ->wherePivot('month',$month)
            ->orderByPivot('sort')
            ->get();*/
    }


}
