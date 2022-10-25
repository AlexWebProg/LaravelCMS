<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscribeConsist extends Model
{
    use HasFactory;
    protected $connection = 'mysql_no_prefix';
    protected $table = 'loncq_subscribe_consist';
    protected $primaryKey = 'id';
    protected $guarded = false;
    public $timestamps = false;

    // Подписка
    public function subscribe() {
        return $this->belongsTo(Subscribe::class,'subscribe_id');
    }

    // Дата пометки на сборку
    public function getAddedStrAttribute() {
        return date('d.m.Y H:i',strtotime($this->added));
    }

}
