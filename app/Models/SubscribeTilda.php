<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscribeTilda extends Model
{
    use HasFactory;
    protected $connection = 'mysql_no_prefix';
    protected $table = 'loncq_subscribe_tilda';
    protected $primaryKey = 'id';
    protected $guarded = false;
    public $timestamps = false;

    // Дата создания в текстовом формате
    public function getCreatedStrAttribute() {
        return date('d.m.Y H:i',strtotime($this->created));
    }

    // Форматирует данные, полученные из Тильды, переводя их в массив
    protected function formatData($data){
        $data = unserialize($data);
        if (!empty($data['payment'])) $data['payment'] = (array) json_decode($data['payment']);
        // Разбираем товар
        $data['subscribe'] = [];
        if (!empty($data['payment']['products'][0])) {
            // Тип подписки
            $data['subscribe']['Тип подписки'] = mb_substr(
                $data['payment']['products'][0]
                ,0,
                mb_strpos(
                    $data['payment']['products'][0],
                    ' (Периодичность',
                    0,
                    'UTF-8'
                ),
                'UTF-8'
            );
            // Разбор других параметров
            $strParameters = mb_substr(
                $data['payment']['products'][0],
                mb_strpos(
                    $data['payment']['products'][0],
                    ' (Периодичность',
                    0,
                    'UTF-8'
                ) + 2,
                -1,
                'UTF-8'
            );
            $arParameters = explode(', ',$strParameters);
            if (count($arParameters)) {
                foreach ($arParameters as $strParameter) {
                    $arParameter = explode(': ',$strParameter);
                    if (!empty($arParameter[0])) {
                        if ($arParameter[0] === 'Учет предпочтений*') {
                            $arParameter[0] = 'Учет предпочтений';
                            $arParameter[1] = mb_substr(
                                $arParameter[1]
                                ,0,
                                mb_strpos(
                                    $arParameter[1],
                                    'руб.',
                                    0,
                                    'UTF-8'
                                ) + 4,
                                'UTF-8'
                            );
                            $data['subscribe']['Учет_предпочтений_сумма'] = preg_replace('/[^[:digit:]]/', '', $arParameter[1]);
                        }
                        $data['subscribe'][$arParameter[0]] = $arParameter[1];
                    }
                    unset($arParameter);
                }
            }
        }
        if (!isset($data['Name'])) $data['Name'] = '';
        if (!isset($data['email'])) $data['email'] = '';
        if (!isset($data['phone'])) $data['phone'] = '';
        if (!isset($data['payment']['amount'])) $data['payment']['amount'] = 0;
        if (!isset($data['subscribe']['Тип подписки'])) $data['subscribe']['Тип подписки'] = '';
        if (!isset($data['subscribe']['Периодичность'])) $data['subscribe']['Периодичность'] = '';
        if (!isset($data['subscribe']['Размер верха'])) $data['subscribe']['Размер верха'] = '';
        if (!isset($data['subscribe']['Размер низа'])) $data['subscribe']['Размер низа'] = '';
        if (!isset($data['subscribe']['Ваш рост'])) $data['subscribe']['Ваш рост'] = '';
        if (!isset($data['subscribe']['Размер стопы'])) $data['subscribe']['Размер стопы'] = '';
        if (!isset($data['subscribe']['Учет предпочтений'])) $data['subscribe']['Учет предпочтений'] = '';
        if (!isset($data['subscribe']['Учет_предпочтений_сумма'])) $data['subscribe']['Учет_предпочтений_сумма'] = 0;
        if (!isset($data['payment']['delivery'])) $data['payment']['delivery'] = '';
        if (!isset($data['payment']['delivery_address'])) $data['payment']['delivery_address'] = '';
        if (!isset($data['payment']['delivery_price'])) $data['payment']['delivery_price'] = 0;
        if (!isset($data['payment']['delivery_fio'])) $data['payment']['delivery_fio'] = '';
        if (!isset($data['payment']['delivery_comment'])) $data['payment']['delivery_comment'] = '';
        return $data;
    }

    // Данные из Тильды
    protected function data(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $this->formatData($value),
        );
    }

}
