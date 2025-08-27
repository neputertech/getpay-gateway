<?php

namespace NeputerTech\GetpayGateway\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Getpay Gateway Controller
 * 
 * Handles payment gateway requests and renders payment-related views
 * 
 * @package NeputerTech\GetpayGateway\Controllers
 * @author Nitish Raj Uprety <nitishuprety@neputer.com>
 */
class GetpayController extends Controller
{
    /**
     * Display the checkout page with payment parameters
     * 
     * Extracts payment parameters from the request and renders the checkout view
     * with all necessary data for payment processing.
     * 
     * @param Request $request HTTP request containing payment parameters
     * @return View The checkout view with payment data
     */
    public function checkout(Request $request): View
    {
        extract($request->all());

        return view('getpay-views::checkout', compact('amount', 'currency', 'refID', 'callbackUrl', 'failUrl', 'businessName', 'imageUrl', 'themeColor', 'orderInfoUi'));
    }

    /**
     * Display the payment processing page
     * 
     * Renders the payment view that handles the actual payment processing
     * and communication with the Getpay gateway.
     * 
     * @return View The payment processing view
     */
    public function payment(): View
    {
        return view('getpay-views::payment');
    }
}
