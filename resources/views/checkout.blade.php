<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Checkout - getpay</title>
    <style>
        body {
            margin: 0;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #f0f0f0;
            font-family: Arial, sans-serif;
        }
        div {
            text-align: center;
        }
        div .processing {
            width: 50px;
            height: 50px;
            border: 5px solid #ccc;
            border-top-color: #3498db;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin: 0 auto 20px;
        }
        div .error {
            width: 80px;
            height: 80px;
            background-color: #e74c3c;
            -webkit-mask: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 15h-1v-1h1v1zm0-2h-1V7h1v8z"/></svg>') no-repeat center;
            mask: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 15h-1v-1h1v1zm0-2h-1V7h1v8z"/></svg>') no-repeat center;
            -webkit-mask-size: 80%;
            mask-size: 80%;
            margin: 0 auto 20px;
        }
        div .success {
            width: 80px;
            height: 80px;
            background-color: #2ecc71;
            -webkit-mask: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg>') no-repeat center;
            mask: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg>') no-repeat center;
            -webkit-mask-size: 80%;
            mask-size: 80%;
            margin: 0 auto 20px;
        }
        p {
            font-size: 18px;
            color: #333;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    <div id="content">
        <div id="icon" class="processing"></div>
        <p id="message">Processing, please wait...</p>
    </div>

    <div id="checkout" style="display: none"></div>

    <script src="{{ config('getpay.bundle_url') }}"></script>

    @component('getpay-views::components.getpay-scripts', compact('refID', 'amount', 'currency', 'callbackUrl', 'failUrl', 'businessName', 'imageUrl', 'themeColor', 'orderInfoUi'))@endcomponent

    <script>
        document.addEventListener('getpay-error', function() {
            document.querySelector('#icon').classList.remove('processing');
            document.querySelector('#icon').classList.add('error');
            document.querySelector('#message').innerHTML = "Error while processing payment!";
        })

        document.addEventListener('getpay-success', function() {
            document.querySelector('#icon').classList.remove('processing');
            document.querySelector('#icon').classList.add('success');
            document.querySelector('#message').innerHTML = "Processing successful. Redirecting to the payment page...";
        })
    </script>
</body>
</html>
