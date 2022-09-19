<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


class SubRequestComment extends Model
{
    use HasFactory;
    protected $connection = 'mysql_no_prefix';
    protected $table = 'loncq_user_subs_requests_comments';
    protected $guarded = false;
    public $timestamps = false;



    // Список комментариев в заявке
    public static function getSubRequestCommentsByRequestId($request_id)
    {
        $request_id = (int) $request_id;
        return DB::select("
            SELECT loncq_user_subs_requests_comments.*, bbadmin_users.name
            FROM loncq_user_subs_requests_comments
            LEFT JOIN bbadmin_users ON bbadmin_users.id = loncq_user_subs_requests_comments.bbadmin_user_id
            WHERE loncq_user_subs_requests_comments.request_id = {$request_id}
            ORDER BY loncq_user_subs_requests_comments.created
        ");
    }
}
