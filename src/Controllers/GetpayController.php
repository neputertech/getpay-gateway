<?php

namespace NeputerTech\GetpayGateway\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class GetpayController extends Controller
{
    public function checkout(Request $request)
    {
        extract($request->all());

        return view('getpay-views::checkout', compact('amount', 'currency', 'refID', 'callbackUrl', 'failUrl', 'businessName', 'imageUrl', 'themeColor', 'orderInfoUi'));
    }

    public function payment()
    {
        return view('getpay-views::payment');
    }
}
