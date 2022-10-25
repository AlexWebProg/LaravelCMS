<?php

namespace App\Http\Controllers\Admin\SubscribeTilda;

use App\Http\Controllers\Controller;
use App\Services\Subscriber\Service;

class BaseController extends Controller
{
    public $subscriber_service;

    public function __construct(Service $service)
    {
        $this->subscriber_service = $service;
    }
}
