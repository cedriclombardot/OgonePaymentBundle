<?php

namespace Pilot\OgonePaymentBundle\Feedback;

class OgoneCodes
{
    const PAY_STATUS_OK = 5;
    const PAY_STATUS_ACCEPT = 9;
    const PAY_STATUS_WILLBE_OK = 51;
    const PAY_STATUS_WILLBE_ACCEPT = 91;

    const PAY_STATUS_REFUSE = 2;

    const PAY_STATUS_NOT_SURE = 52;
    const PAY_STATUS_PAY_NOT_SURE = 92;

    const PAY_STATUS_CANCELLED = 1;

    public static function isPayed($status)
    {
        return in_array($status, array(self::PAY_STATUS_OK, self::PAY_STATUS_ACCEPT, self::PAY_STATUS_WILLBE_OK, self::PAY_STATUS_WILLBE_ACCEPT));
    }

    public static function isRefused($status)
    {
        return in_array($status, array(self::PAY_STATUS_REFUSE));
    }

    public static function isUncertain($status)
    {
        return in_array($status, array(self::PAY_STATUS_NOT_SURE, self::PAY_STATUS_PAY_NOT_SURE));
    }
}
