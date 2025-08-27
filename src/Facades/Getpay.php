<?php

namespace NeputerTech\GetpayGateway\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Getpay Gateway Facade
 * 
 * Laravel facade for the Getpay payment gateway service.
 * Provides static access to GetpayService methods through Laravel's facade system.
 * 
 * @package NeputerTech\GetpayGateway\Facades
 * @author Nitish Raj Uprety <nitishuprety@neputer.com>
 * 
 * @method static \Illuminate\Http\RedirectResponse init(string $refID, float $amount, string $currency, string $callbackUrl, string $failUrl, ?string $businessName = null, ?string $imageUrl = null, ?string $themeColor = null, ?string $orderInfoUi = null)
 * @method static array verify(string $token)
 * 
 * @see \NeputerTech\GetpayGateway\Services\GetpayService
 */
class Getpay extends Facade
{
    /**
     * Get the registered name of the component
     * 
     * Returns the service container binding key for the GetpayService.
     * 
     * @return string The facade accessor key
     */
    protected static function getFacadeAccessor(): string
    {
        return 'getpay';
    }
}
