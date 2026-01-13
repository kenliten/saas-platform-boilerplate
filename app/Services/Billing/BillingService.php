<?php

namespace App\Services\Billing;

use App\Services\Billing\Drivers\StripeBillingDriver;
use App\Services\Billing\Drivers\PayPalBillingDriver;
use App\Services\Billing\Drivers\NoneBillingDriver;

class BillingService
{
    protected static $driver = null;

    public static function getDriver(): BillingInterface
    {
        if (self::$driver === null) {
            $driverName = getenv('BILLING_DRIVER') ?: 'none';

            switch ($driverName) {
                case 'stripe':
                    self::$driver = new StripeBillingDriver();
                    break;
                case 'paypal':
                    self::$driver = new PayPalBillingDriver();
                    break;
                case 'none':
                default:
                    self::$driver = new NoneBillingDriver();
                    break;
            }
        }
        return self::$driver;
    }
}
