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

class RefundOrder
{
    /**
     * @var \Volt
     */
    private $module;

    public function __construct(
        \Volt $module
    ) {
        $this->module = $module;
    }

    /**
     * Check if refund has active
     *
     * @param string $paymentId
     *
     * @return bool
     * @throws \Exception
     */
    public function checkIsRefundActive(string $paymentId): bool
    {
        if (!$paymentId) {
            return false;
        }

        try {
            $refund = $this->module->api->request('GET', 'payments/' . $paymentId . '/refund-details');
            if ($refund->refundAvailable) {
                return (bool) $refund->refundAvailable;
            }
        } catch (\Exception $e) {
            throw new \Exception('Error in the method of checking the availability of the order return option');
        }

        return false;
    }

    /**
     * Execute refund
     *
     * @param string $paymentId
     * @param string $paymentType
     * @param null $amount
     *
     * @return bool|void
     * @throws \Exception
     */
    public function requestRefund(string $paymentId, string $paymentType, $amount = null)
    {
        $payload = [];
        $reference = 'tes' . $amount;

        if ($paymentType === 'partial' && !empty($amount)) {
            $payload = [
                'amount' => (int) $amount,
                'externalReference' => $reference,
            ];
        }

        try {
            return $this->module->api->request('POST', 'payments/' . $paymentId . '/request-refund', $payload);
        } catch (\Exception $e) {
            throw new \Exception('Error in the method of execution of the order return');
        }
    }
}
