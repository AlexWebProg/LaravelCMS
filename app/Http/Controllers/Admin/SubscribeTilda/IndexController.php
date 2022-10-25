<?php

namespace App\Http\Controllers\Admin\SubscribeTilda;

use App\Models\SubscribeTilda;

class IndexController extends BaseController
{
    public function __invoke($processed = 0)
    {
        // Вывод таблицы
        $ordersFromTilda = SubscribeTilda::where('processed',$processed)
            ->orderBy('id','desc')
            ->get();
        return view('admin.subscribeTilda.index', compact('ordersFromTilda'));
    }
    // ----------------------------------------------------------------------------------------------

}
