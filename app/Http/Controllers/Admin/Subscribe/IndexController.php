<?php

namespace App\Http\Controllers\Admin\Subscribe;

class IndexController extends BaseController
{
    public function __invoke()
    {
        // Вывод таблицы
        return view('admin.subscribes.index');
    }
    // ----------------------------------------------------------------------------------------------

}
