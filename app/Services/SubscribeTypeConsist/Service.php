<?php

namespace App\Services\SubscribeTypeConsist;

use App\Models\SubscribeSettings;
use Illuminate\Support\Facades\DB;

class Service
{
    public function updateSubscribeTypeConsist(SubscribeSettings $subscribeType, string $month, array $data){
        $arConsist = [];
        foreach ($data['product_id'] as $sort => $product_id) {
            $arConsist[] = [
                'product_id' => $product_id,
                'qnt' => $data['qnt'][$sort],
                'sort' => $sort,
                'month' => $month
            ];
        }
        $arNotification = [
            'class' => 'success',
            'message' => 'Данные успешно обновлены'
        ];
        DB::beginTransaction();
        try {
            $subscribeType->subscribeTypeConsist()->detach($subscribeType->subscribeTypeConsistByMonth($month));
            $subscribeType->subscribeTypeConsist()->attach($arConsist);
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
}
