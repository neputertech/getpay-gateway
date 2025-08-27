<?php

namespace NeputerTech\GetpayGateway\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

/**
 * Getpay Scripts Component
 * 
 * Laravel Blade component that renders the necessary JavaScript and HTML
 * for Getpay payment gateway integration. Handles payment parameters
 * and provides the frontend interface for payment processing.
 * 
 * @package NeputerTech\GetpayGateway\Components
 * @author Nitish Raj Uprety <nitishuprety@neputer.com>
 */
class GetpayScripts extends Component
{
    /**
     * Unique reference ID for the transaction
     * 
     * @var string
     */
    public string $refID;

    /**
     * Payment amount
     * 
     * @var float
     */
    public float $amount;

    /**
     * Currency code (e.g., 'USD', 'NPR', 'EUR')
     * 
     * @var string
     */
    public string $currency;

    /**
     * URL to redirect after successful payment
     * 
     * @var string
     */
    public string $callbackUrl;

    /**
     * URL to redirect after failed payment
     * 
     * @var string
     */
    public string $failUrl;

    /**
     * Create a new GetpayScripts component instance
     * 
     * Initializes the component with payment parameters required
     * for Getpay gateway integration.
     * 
     * @param string $refID Unique reference ID for the transaction
     * @param float $amount Payment amount
     * @param string $currency Currency code
     * @param string $callbackUrl Success callback URL
     * @param string $failUrl Failure callback URL
     */
    public function __construct(string $refID, float $amount, string $currency, string $callbackUrl, string $failUrl)
    {
        $this->refID = $refID;
        $this->amount = $amount;
        $this->currency = $currency;
        $this->callbackUrl = $callbackUrl;
        $this->failUrl = $failUrl;
    }

    /**
     * Get the view / contents that represent the component
     * 
     * Returns the Blade view that contains the JavaScript and HTML
     * necessary for Getpay payment gateway integration.
     * 
     * @return View The component view
     */
    public function render(): View
    {
        return view('getpay-views::components.getpay-scripts');
    }
}
