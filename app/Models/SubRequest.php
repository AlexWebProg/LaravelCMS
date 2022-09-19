<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


class SubRequest extends Model
{
    use HasFactory;
    protected $connection = 'mysql_no_prefix';
    protected $table = 'loncq_user_subs_requests';
    protected $guarded = false;
    public $timestamps = false;
    protected static $arStatusCodes = [
        0 => 'new',
        1 => 'answered',
        2 => 'updated',
        3 => 'closed'
    ];
    protected static $arStatuses = [
        'allOpened' => [
            'status_id' => [0, 1, 2],
            'status_code' => 'allOpened',
            'status_name' => 'Открыта',
            'status_name_many' => 'Все открытые',
            'index_page_title' => 'Все открытые заявки по подпискам',
            'color_type' => 'primary',
            'icon' => 'fa fa-bolt'
        ],
        'new' => [
            'status_id' => 0,
            'status_code' => 'new',
            'status_name' => 'Новая',
            'status_name_many' => 'Новые',
            'index_page_title' => 'Новые заявки по подпискам',
            'color_type' => 'info',
            'icon' => 'fa fa-star'
        ],
        'answered' => [
            'status_id' => 1,
            'status_code' => 'answered',
            'status_name' => 'Обработана',
            'status_name_many' => 'Обработанные',
            'index_page_title' => 'Обработанные нами заявки по подпискам',
            'color_type' => 'success',
            'icon' => 'fa fa-comment'
        ],
        'updated' => [
            'status_id' => 2,
            'status_code' => 'updated',
            'status_name' => 'Обновлена',
            'status_name_many' => 'Обновлённые',
            'index_page_title' => 'Обновлённые подписчиком заявки по подпискам',
            'color_type' => 'warning',
            'icon' => 'fa fa-comments'
        ],
        'closed' => [
            'status_id' => 3,
            'status_code' => 'closed',
            'status_name' => 'Закрыта',
            'status_name_many' => 'Закрытые',
            'index_page_title' => 'Закрытые заявки по подпискам',
            'color_type' => 'danger',
            'icon' => 'fa fa-check'
        ],
    ];

    // Возвращает статусы заявок
    public static function getStatuses()
    {
        return self::$arStatuses;
    }

    // Возвращает код статуса заявки по её id
    public static function getStatusCode($id)
    {
        return self::$arStatusCodes[$id];
    }

    // Форматирует поля заявки по подписке
    public static function formatSubRequest(&$arSubRequest) {
        $arSubRequest->subscribe_info = unserialize($arSubRequest->subscribe_info);
        $arSubRequest->actions_text_in_table = !empty($arSubRequest->actions) ? $arSubRequest->actions : $arSubRequest->comment;
        $arSubRequest->created_text = Carbon::parse($arSubRequest->created)->translatedFormat('d.m.Y H:i');
        $arSubRequest->updated_text = empty($arSubRequest->updated)? '-' : Carbon::parse($arSubRequest->updated)->translatedFormat('d.m.Y H:i');
    }

    // Получает количество заявок в каждом статусе и возвращает основную информпцию о них
    public static function getSubRequestsBasicInfo()
    {
        $arStatusesWithQnt = self::getStatuses();
        $arStatusQntInDB = DB::select('SELECT COUNT(id) as qnt, status FROM loncq_user_subs_requests GROUP BY status');

        foreach ($arStatusesWithQnt as &$arStatus) {
            $arStatus['requests_qnt'] = 0;
        }

        if (!empty($arStatusQntInDB) and count($arStatusQntInDB)) {
            foreach ($arStatusQntInDB as $arStatusRow) {
                $arStatusesWithQnt[self::getStatusCode($arStatusRow->status)]['requests_qnt'] = $arStatusRow->qnt;
                if ($arStatusRow->status != 3) {
                    $arStatusesWithQnt['allOpened']['requests_qnt'] += $arStatusRow->qnt;
                }
            }
        }

        return $arStatusesWithQnt;
    }

    // Список заявок по коду статуса
    public static function getSubRequestsByStatusCode($status_code = 'allOpened')
    {
        $status_ids = self::getStatuses()[$status_code]['status_id'];
        if (is_array($status_ids)) $status_ids = implode(',', $status_ids);
        $arSubRequests = DB::select("
            SELECT loncq_user_subs_requests.*
            FROM loncq_user_subs_requests
            WHERE loncq_user_subs_requests.status IN ({$status_ids})
        ");
        if (!empty($arSubRequests) and count($arSubRequests)) {
            foreach ($arSubRequests as &$arSubRequest) {
                self::formatSubRequest($arSubRequest);
            }
        }
        return $arSubRequests;
    }
}
