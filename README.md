# GetPay Gateway for Laravel (Nepal)

A lightweight Laravel package to integrate the GetPay Nepal payment gateway.

- PHP: ^8.0
- Laravel (Illuminate): 9.x, 10.x, 11.x, 12.x
- Package name: `neputertech/getpay-gateway`

This package ships a Facade-first API, pre-wired routes, and publishable views/config to help you start accepting payments quickly.

## Installation

```bash
composer require neputertech/getpay-gateway
```

Laravel package auto-discovery will register the service provider automatically.

## Publish assets (optional)

- Config:

```bash
php artisan vendor:publish --provider="NeputerTech\GetpayGateway\GetpayServiceProvider" --tag=getpay-config
```

- Views (if you want to customize the built-in screens):

```bash
php artisan vendor:publish --tag=getpay-views
```

## Environment variables

Add the following to your `.env` (values provided by GetPay):

```dotenv
GETPAY_PAP_INFO=your-pap-info
GETPAY_OPR_KEY=your-operator-key   # optional depending on your account
GETPAY_INS_KEY=your-ins-key        # optional depending on your account
GETPAY_BASE_URL=https://api.getpay.example   # e.g. https://api.getpay.com.np
GETPAY_BUNDLE_URL=https://widget.getpay.example/bundle.js  # GetPay checkout bundle URL
```

Config keys live in `config/getpay.php`:

- `getpay.pap_info`
- `getpay.opr_key`
- `getpay.ins_key`
- `getpay.base_url`
- `getpay.bundle_url`

## Quick start (Facade-first)

Import and use the Facade `Getpay` to kick off a checkout and to verify the payment on the callback.

### 1) Start a checkout

```php
<?php

use Illuminate\Http\Request;
use NeputerTech\GetpayGateway\Facades\Getpay;

class PaymentController
{
    public function pay(Request $request)
    {
        $refID = 'ORD-'.now()->timestamp; // your unique order reference

        // Redirects to the package's checkout route with all required details
        return Getpay::init(
            refID: $refID,
            amount: 1000.00,          // numeric value
            currency: 'NPR',          // currency code
            callbackUrl: route('payments.getpay.callback'),
            failUrl: route('payments.getpay.failed'),
            businessName: 'Acme Inc', // optional
            imageUrl: asset('images/logo.png'), // optional
            themeColor: '#2F855A',    // optional (hex)
            orderInfoUi: null         // optional (custom UI payload)
        );
    }
}
```

This redirects to the built-in `getpay.checkout` page which loads the GetPay bundle and starts the checkout.

### 2) Handle the callback and verify

GetPay will redirect back to your `callbackUrl` with a token. Verify it using the Facade:

```php
<?php

use Illuminate\Http\Request;
use NeputerTech\GetpayGateway\Exceptions\GetpayException;
use NeputerTech\GetpayGateway\Facades\Getpay;

class PaymentController
{
    public function callback(Request $request)
    {
        $token = $request->query('token');

        try {
            $status = Getpay::verify($token); // returns the gateway response array

            // Example success check
            if (data_get($status, 'message') === 'SUCCESS') {
                // TODO: mark order as paid using $status
                return redirect()->route('thankyou')->with('status', 'Payment successful');
            }

            return redirect()->route('payments.getpay.failed')
                ->withErrors('Payment not successful.');
        } catch (GetpayException $e) {
            // 4001: Token decoding failed
            // 4002: Invalid token data
            // 4003: Gateway error
            // 4004: Transaction failed
            return redirect()->route('payments.getpay.failed')
                ->withErrors($e->getMessage());
        }
    }

    public function failed()
    {
        return back()->withErrors('Payment cancelled/failed.');
    }
}
```

Example routes:

```php
// routes/web.php
Route::get('/payments/getpay/callback', [PaymentController::class, 'callback'])
    ->name('payments.getpay.callback');

Route::get('/payments/getpay/failed', [PaymentController::class, 'failed'])
    ->name('payments.getpay.failed');
```

## What the package provides

- Service container binding: key `getpay` (singleton)
- Facade class: `NeputerTech\GetpayGateway\Facades\Getpay`
- Shipped routes (auto-loaded via the service provider):
  - `GET /getpay/checkout` named `getpay.checkout` – used by `Getpay::init()` for the handoff screen
  - `GET /getpay/payment` named `getpay.payment` – basic payment view (optional)
- View namespace: `getpay-views::`
  - `getpay-views::checkout`
  - `getpay-views::payment`
- Blade component namespace: `getpay-*` (registered), e.g. you can render the checkout script component if customizing views:

```blade
{{-- Only if you customize/publish views --}}
<div id="checkout"></div>
<script src="{{ config('getpay.bundle_url') }}"></script>
<x-getpay-getpay-scripts
    :refID="$refID"
    :amount="$amount"
    :callbackUrl="$callbackUrl"
    :failUrl="$failUrl"
    currency="NPR"
/>
```

Note: The package defaults should work out of the box. Customize only if you need to fully control the UI.

## Testing tips

You can fake the gateway verification call:

```php
use Illuminate\Support\Facades\Http;
use NeputerTech\GetpayGateway\Facades\Getpay;

Http::fake([
    config('getpay.base_url').'/merchant-status' => Http::response([
        'message' => 'SUCCESS',
        'id' => 'example-id',
    ], 200),
]);

$result = Getpay::verify(base64_encode(json_encode([
    'id' => 'example-id',
    'oprSecret' => 'secret',
])));

$this->assertSame('SUCCESS', data_get($result, 'message'));
```

## Troubleshooting

- Invalid or missing token → ensure your `callbackUrl` receives a `token` query param and pass it to `Getpay::verify()`.
- 4003 "Gateway error" → verify `GETPAY_BASE_URL` is reachable and correct.
- "Transaction failed" → the gateway returned a non-success status; inspect the response array from `verify()`.

## License

MIT © NeputerTech
