<?php

namespace App\Http\Controllers\Admin\SubscribeProduct;

use App\Http\Controllers\Controller;
use App\Services\SubscribeProduct\Service;

class BaseController extends Controller
{
    public $service;

    public function __construct(Service $service)
    {
        $this->service = $service;
    }
}