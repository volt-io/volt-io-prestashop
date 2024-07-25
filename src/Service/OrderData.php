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

namespace Volt\Service;

if (!defined('_PS_VERSION_')) {
    exit;
}

use Volt\Util\Helper;

class OrderData
{
    /**
     * @var \AddressCore
     */
    private $address;
    /**
     * @var \Customer
     */
    private $customer;
    /**
     * @var \Context
     */
    private $context;

    public function __construct(
        \AddressCore $address,
        \Customer $customer,
        \Context $context
    ) {
        $this->address = $address;
        $this->customer = $customer;
        $this->context = $context;
    }

    /**
     * Get order data
     *
     * @param \Cart $cart
     *
     * @return array
     */
    public function getData(\Cart $cart): array
    {
        $customer = $this->context->customer;
        $orderTotal = (string) ($cart->getOrderTotal(true, \Cart::BOTH) * 100);

        $customerEmail = $this->context->cookie->email ?? '';
        $currency = $this->context->currency->iso_code ?? 'GBP';

        $payerReference = 'ref' . $customer->id;

        return [
            'currencyCode' => $currency,
            'amount' => (int) $orderTotal,
            'type' => 'OTHER',
            'uniqueReference' => $this->generateReference($cart),
            'payer' => [
                'reference' => $payerReference,
                'email' => $customerEmail,
                'firstName' => $this->address->firstname,
                'lastName' => $this->address->lastname,
                'ip' => Helper::getClientIp(),
            ],
            'notificationUrl' => $this->context->link->getModuleLink(
                'volt',
                'notifications',
                [
                    'cart_id' => $cart->id,
                    'customer_id' => $customer->id,
                ]
            ),
            'paymentPendingUrl' => $this->context->link->getModuleLink('volt', 'pending', []),
            'paymentCancelUrl' => $this->context->link->getModuleLink(
                'volt',
                'cancel',
                [
                    'cart_id' => $cart->id,
                    'customer_id' => $customer->id,
                ]
            ),
            'paymentSuccessUrl' => $this->context->link->getModuleLink(
                'volt',
                'success',
                [
                    'cart_id' => $cart->id,
                    'customer_id' => $customer->id,
                    'reference' => Helper::createCrc($cart, $customer),
                ]
            ),
            'paymentFailureUrl' => $this->context->link->getModuleLink(
                'volt',
                'failure',
                [
                    'cart_id' => $cart->id,
                    'customer_id' => $customer->id,
                ]
            ),
        ];
    }

    /**
     * Reference generate
     *
     * @param $cart
     *
     * @return string
     */
    public function generateReference($cart): string
    {
        return uniqid('c' . $cart->id);
    }
}
