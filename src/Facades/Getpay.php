<?php

namespace NeputerTech\GetpayGateway\Facades;

use Illuminate\Support\Facades\Facade;

class Getpay extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'getpay';
    }
}
