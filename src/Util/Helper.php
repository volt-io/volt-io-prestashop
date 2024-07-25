<?php
/**
 * NOTICE OF LICENSE.
 * This file is licenced under the Software License Agreement.
 * With the purchase or the installation of the software in your application
 * you accept the licence agreement.
 * You must not modify, adapt or create derivative works of this source code
 *
 * @author    Volt Technologies Holdings Limited
 * @copyright 2023, Volt Technologies Holdings Limited
 * @license   LICENSE.txt
 */

declare(strict_types=1);

namespace Volt\Util;

if (!defined('_PS_VERSION_')) {
    exit;
}
class Helper
{
    public static function getFields(): array
    {
        return [
            'VOLT_ENV',
            'VOLT_ENV_SWITCH',
            'VOLT_PROD_CLIENT_ID',
            'VOLT_PROD_CLIENT_SECRET',
            'VOLT_PROD_NOTIFICATION_SECRET',
            'VOLT_PROD_USERNAME',
            'VOLT_PROD_PASSWORD',
            'VOLT_SANDBOX_CLIENT_ID',
            'VOLT_SANDBOX_CLIENT_SECRET',
            'VOLT_SANDBOX_NOTIFICATION_SECRET',
            'VOLT_SANDBOX_USERNAME',
            'VOLT_SANDBOX_PASSWORD',
            'VOLT_CUSTOM_STATE',
            'VOLT_PENDING_STATE_ID',
            'VOLT_NOT_PAID_STATE_ID',
            'VOLT_SUCCESS_STATE_ID',
            'VOLT_FAILURE_STATE_ID',
        ];
    }

    public static function getClientIp()
    {
        $ipHeaders = [
            'HTTP_CLIENT_IP',
            'HTTP_X_FORWARDED_FOR',
            'HTTP_X_FORWARDED',
            'HTTP_FORWARDED_FOR',
            'HTTP_FORWARDED',
            'REMOTE_ADDR',
        ];

        foreach ($ipHeaders as $header) {
            if (($ip = getenv($header)) !== false) {
                return $ip;
            }
        }

        return self::getClientIpServer();
    }

    /**
     * Get client server ip
     *
     * @codeCoverageIgnore
     */
    public static function getClientIpServer()
    {
        if (isset($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } elseif (isset($_SERVER['HTTP_X_FORWARDED'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED'];
        } elseif (isset($_SERVER['HTTP_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_FORWARDED_FOR'];
        } elseif (isset($_SERVER['HTTP_FORWARDED'])) {
            $ip = $_SERVER['HTTP_FORWARDED'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'] ?? 'UNKNOWN';
        }

        return $ip;
    }

    public static function createCrc($cart, $customer): string
    {
        return md5($cart->id . $customer->secure_key);
    }
}
