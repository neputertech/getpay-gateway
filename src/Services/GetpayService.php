<?php

namespace NeputerTech\GetpayGateway\Services;

use Illuminate\Support\Facades\Http;
use NeputerTech\GetpayGateway\Exceptions\GetpayException;
use Throwable;

class GetpayService
{
    public function init(
        string $refID,
        float $amount,
        string $currency,
        string $callbackUrl,
        string $failUrl,
        ?string $businessName = null,
        ?string $imageUrl = null,
        ?string $themeColor = null,
        ?string $orderInfoUi = null,
    )
    {
        return to_route('getpay.checkout', compact('amount', 'currency', 'refID', 'callbackUrl', 'failUrl', 'businessName', 'imageUrl', 'themeColor', 'orderInfoUi'));
    }

    public function verify(string $token)
    {
        try {
            $decodedToken = json_decode(base64_decode($token), true);
            $id = $decodedToken['id'] ?? null;
            $oprSecret = $decodedToken['oprSecret'] ?? null;
        } catch (Throwable $e) {
            throw new GetpayException('Token decoding failed', 4001);
        }

        if (!$id || !$oprSecret) {
            throw new GetpayException('Invalid token data', 4002);
        }

        $statusResponse = $this->verifyPaymentStatus($id);

        if($statusResponse['message'] === 'SUCCESS') {
            return $statusResponse;
        } else {
            throw new GetpayException('Transaction failed', 4004);
        }
    }

    private function verifyPaymentStatus($id)
    {
        $endpoint = config('getpay.base_url') . '/merchant-status';
        $papInfo = config('getpay.pap_info');

        $payload = [
            'id' => $id,
            'papInfo' => $papInfo,
        ];

        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post($endpoint, $payload);

            return $response->json();
        } catch (Throwable $e) {
            throw new GetpayException('Gateway error', 4003, $e);
        }
    }
}
