<?php

namespace NeputerTech\GetpayGateway\Components;

use Illuminate\View\Component;

class GetpayScripts extends Component
{
    public string $refID;
    public float $amount;
    public string $currency;
    public string $callbackUrl;
    public string $failUrl;

    public function __construct(string $refID, float $amount, string $currency, string $callbackUrl, string $failUrl)
    {
        $this->refID = $refID;
        $this->amount = $amount;
        $this->currency = $currency;
        $this->callbackUrl = $callbackUrl;
        $this->failUrl = $failUrl;
    }

    public function render()
    {
        return view('getpay-views::components.getpay-scripts');
    }
}
