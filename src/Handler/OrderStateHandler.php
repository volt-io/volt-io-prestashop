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

namespace Volt\Handler;

if (!defined('_PS_VERSION_')) {
    exit;
}

use Volt\Adapter\ConfigurationAdapter;
use Volt\Config\Config;
use Volt\Repository\TransactionRepository;

class OrderStateHandler
{
    public $orderHistory;
    private $configuration;
    private $transactionsRepository;

    public function __construct(
        \OrderHistory $orderHistory,
        ConfigurationAdapter $configuration,
        TransactionRepository $transactionsRepository
    ) {
        $this->orderHistory = $orderHistory;
        $this->configuration = $configuration;
        $this->transactionsRepository = $transactionsRepository;
    }

    /**
     * Update orders statuses.
     *
     * @param \Order $order
     * @param string $paymentId
     * @param bool $error
     * @param bool $refund
     * @param bool $notPaid
     *
     * @return void
     */
    public function changeOrdersState(
        \Order $order,
        string $paymentId,
        bool $error = false,
        bool $refund = false,
        bool $notPaid = false
    ): void {
        $reference = $order->reference;
        $referencedOrders = \Order::getByReference($reference)->getResults();
        foreach ($referencedOrders as $orderObject) {
            if (!is_null($orderObject->id)) {
                $this->changeOrderStateById($orderObject, $paymentId, $error, $refund, $notPaid);
            }
        }
    }

    /**
     * Update order status.
     *
     * @param Order $order
     * @param string $paymentId
     * @param bool $error
     * @param bool $refund
     * @param bool $notPaid
     *
     * @return void
     */
    private function changeOrderStateById(
        \Order $order,
        string $paymentId,
        bool $error = false,
        bool $refund = false,
        bool $notPaid = false
    ): void {
        $voltStates = $this->configuration->get('VOLT_CUSTOM_STATE') ?? false;

        if ($refund) {
            $orderStateId = $this->configuration->get('PS_OS_REFUND');
        } elseif ($notPaid) {
            $orderStateId = $this->configuration->get('PS_OS_CANCELED');

            if ($voltStates) {
                $orderStateId = $this->configuration->get(Config::VOLT_NOT_PAID);
            }
        } else {
            $orderStateId = !$error ?
                $this->configuration->get('PS_OS_PAYMENT') :
                $this->configuration->get('PS_OS_ERROR');

            if ($voltStates) {
                $orderStateId = !$error ?
                    $this->configuration->get(Config::VOLT_SUCCESS) :
                    $this->configuration->get(Config::VOLT_FAILURE);
            }
        }

        $allOrderStatuses = $this->transactionsRepository->getOrderStatusHistory($order->id);
        $orderStatusesHistory = $this->getOrderStatusesByHistory($allOrderStatuses);

        if (!in_array($orderStateId, $orderStatusesHistory)) {
            if (!$error && !$refund) {
                $order->addOrderPayment($order->getOrdersTotalPaid(), 'volt', $paymentId);
            }
            $this->orderHistory->id_order = $order->id;
            $this->orderHistory->changeIdOrderState(
                (int) $orderStateId,
                (int) $order->id,
                true
            );
            $this->orderHistory->addWithemail(true);
        }
    }

    public function getOrderStatusesByHistory($states): array
    {
        $stateArray = [];

        if ($states) {
            foreach ($states as $state) {
                foreach ($state as $stateID) {
                    $stateArray[] = $stateID;
                }
            }
        }

        return $stateArray;
    }
}
