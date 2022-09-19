<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


class Subscriber extends Model
{
    use HasFactory;
    protected $connection = 'mysql_no_prefix';
    protected $table = 'loncq_user';
    protected $primaryKey = 'user_id';
    protected $guarded = false;
    public $timestamps = false;
    protected $appends = [
        'phone_str', // Телефон в формате +7 ...
    ];

    // Телефон в текстовом формате
    public function getPhoneStrAttribute() {
        return phoneMask($this->phone);
    }

    // Подписки подписчика
    public function subscribes()
    {
        return $this->hasMany(Subscribe::class, 'user_id', 'user_id');
    }

    // Получает подписчика по email и телефону
    public static function getSubscriber($email,$phone) {
        $subscriber = self::where('email', $email)->where('phone',$phone)->first();
        if (empty($subscriber->user_id)) {
            $subscriber = self::where('phone',$phone)->first();
        }
        if (empty($subscriber->user_id)) {
            $subscriber = self::where('email', $email)->first();
        }
        return $subscriber;
    }

    // Создание подписчика
    public static function addSubscriber($arSubscriber){
        $arSubscriber['password'] = sha1(strMakeRand(5,5,0,0,1));
        $arSubscriber['subscriber'] = 1;
        return self::create($arSubscriber);
    }

    // Получает всех подписчиков для общего списка
    public static function getAllSubscribers(){
        return self::select('loncq_user.*', DB::raw("group_concat(loncq_subscribe_settings.value) as subscribe_names"))
            ->from('loncq_user')
            ->leftJoin('loncq_user_subscribes', 'loncq_user_subscribes.user_id', 'loncq_user.user_id')
            ->leftJoin('loncq_subscribe_settings', 'loncq_subscribe_settings.id', 'loncq_user_subscribes.subscribe_type_id')
            ->groupBy('loncq_user.user_id')
            ->where('loncq_user.subscriber',1)
            ->get();
    }

}
