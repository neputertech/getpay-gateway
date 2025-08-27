<?php

namespace NeputerTech\GetpayGateway\Exceptions;

use Exception;

/**
 * Getpay Gateway Exception
 * 
 * Custom exception class for handling Getpay payment gateway related errors.
 * Extends the base PHP Exception class to provide specific error handling
 * for payment gateway operations.
 * 
 * Common error codes:
 * - 4001: Token decoding failed
 * - 4002: Invalid token data
 * - 4003: Gateway communication error
 * - 4004: Transaction verification failed
 * 
 * @package NeputerTech\GetpayGateway\Exceptions
 * @author Nitish Raj Uprety <nitishuprety@neputer.com>
 */
class GetpayException extends Exception
{

}
