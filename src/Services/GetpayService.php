<?php

namespace NeputerTech\GetpayGateway\Services;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Http;
use NeputerTech\GetpayGateway\Exceptions\GetpayException;
use Throwable;

/**
 * Getpay Gateway Service
 * 
 * Main service class for handling Getpay payment gateway operations including
 * payment initialization, verification, and status checking.
 * 
 * @package NeputerTech\GetpayGateway\Services
 * @author Nitish Raj Uprety <nitishuprety@neputer.com>
 */
class GetpayService
{
    /**
     * Initialize a payment transaction
     * 
     * Creates a payment session by redirecting to the checkout page with all
     * necessary payment parameters for processing the transaction.
     * 
     * @param string $refID Unique reference ID for the transaction
     * @param float $amount Payment amount
     * @param string $currency Currency code (e.g., 'USD', 'EUR')
     * @param string $callbackUrl URL to redirect after successful payment
     * @param string $failUrl URL to redirect after failed payment
     * @param string|null $businessName Optional business name for display
     * @param string|null $imageUrl Optional business logo URL
     * @param string|null $themeColor Optional theme color for payment UI
     * @param string|null $orderInfoUi Optional order information for display
     * @return RedirectResponse Redirect to checkout page
     */
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
    ): RedirectResponse
    {
        return to_route('getpay.checkout', compact('amount', 'currency', 'refID', 'callbackUrl', 'failUrl', 'businessName', 'imageUrl', 'themeColor', 'orderInfoUi'));
    }

    /**
     * Verify a payment transaction
     * 
     * Decodes the payment token and verifies the transaction status with
     * the Getpay gateway to confirm successful payment.
     * 
     * @param string $token Base64 encoded payment token from gateway
     * @return array Payment verification response data
     * @throws GetpayException When token decoding fails (4001)
     * @throws GetpayException When token data is invalid (4002)
     * @throws GetpayException When transaction verification fails (4004)
     */
    public function verify(string $token): array
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

    /**
     * Verify payment status with Getpay gateway
     * 
     * Makes an API call to the Getpay merchant status endpoint to check
     * the current status of a payment transaction.
     * 
     * @param string $id Transaction ID to verify
     * @return array API response containing payment status
     * @throws GetpayException When gateway communication fails (4003)
     */
    private function verifyPaymentStatus(string $id): array
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
