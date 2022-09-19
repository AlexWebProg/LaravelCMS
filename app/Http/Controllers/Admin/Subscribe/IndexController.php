<?php

namespace App\Http\Controllers\Admin\Subscribe;

use App\Models\Subscribe;

class IndexController extends BaseController
{
    public function __invoke()
    {
        // Ближайшие месяцы для отправки
        $arPlannedDataLine = Subscribe::getPlannedDataLine();
        // Вывод таблицы
        return view('admin.subscribes.index', compact( 'arPlannedDataLine'));
    }
    // ----------------------------------------------------------------------------------------------

}
