<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscribeProduct extends Model
{
    use HasFactory;
    protected $connection = 'mysql_no_prefix';
    protected $table = 'loncq_subscribe_products';
    protected $primaryKey = 'id';
    protected $guarded = false;
    public $timestamps = false;

}
