<?php

namespace App\Http\Controllers\Admin\Main;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SubRequest;

class IndexController extends Controller
{
    public function __invoke()
    {
        return view('admin.main.index');
    }

}
