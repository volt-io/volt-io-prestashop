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

use Configuration as Cfg;
use Volt\Config\Config;

if (!defined('_PS_VERSION_')) {
    exit;
}

class VoltPaymentModuleFrontController extends ModuleFrontController
{
    public $ssl = true;
    public $display_column_left = false;
    private $transitionRepository;

    /**
     * @throws PrestaShopDatabaseException
     * @throws PrestaShopException
     */
    public function initContent()
    {
        parent::initContent();

        $this->transitionRepository = $this->get('volt.repository.transaction');
        $paymentId = Tools::getValue('volt_payment_id');
        if ($paymentId) {
            $this->initTransaction($paymentId);
        }
    }

    public function initTransaction($paymentId)
    {
        try {
            $transactionRepository = $this->container->get('volt.repository.transaction');

            if (!$transactionRepository->isTransactionExists($paymentId)) {
                $transactionRepository->initTransaction($paymentId);
            }
        } catch (\Exception $exception) {
            \PrestaShopLogger::addLog($exception, 3);
        }
        $context = $this->module->getContext();
        $cart = $context->cart;
        $customer = $context->customer;

        if ($this->createOrder($cart, $customer, $paymentId)) {
            Tools::redirect(
                $this->context->link->getModuleLink(
                    'volt',
                    'success',
                    [
                        'order_id' => $this->module->currentOrder,
                    ]
                )
            );
        } else {
            \PrestaShopLogger::addLog('Volt - Error payment controller', 3);
            Tools::redirect(
                $this->context->link->getModuleLink(
                    'volt',
                    'failure'
                )
            );
        }
    }

    /**
     * Create order
     *
     * @param $cart
     * @param $customer
     * @param $paymentId
     *
     * @return bool
     */
    private function createOrder($cart, $customer, $paymentId): bool
    {
        $orderTotal = (float) $cart->getOrderTotal();
        $state = 'PENDING';
        $res = false;

        $this->module->debug($orderTotal, 'transactionRepository3');

        if (!$cart->OrderExists()) {
            $this->voltValidateOrder($cart->id, $orderTotal, $customer);

            if (isset($this->module->currentOrder) && !empty($this->module->currentOrder)) {
                $payload = [
                    'payment_id' => $paymentId,
                    'order_id' => $this->module->currentOrder,
                    'cart' => $cart,
                    'state' => $state,
                ];

                $this->module->debug($payload, 'transactionRepository');
                $this->transitionRepository->updateTransactionOrderByPaymentId(
                    $paymentId,
                    $this->module->currentOrder,
                    $cart,
                    $state
                );

                $res = true;
            }
        }

        return $res;
    }

    /**
     * Create prestashop order
     *
     * @param $cardId
     * @param $orderTotal
     * @param $customer
     */
    private function voltValidateOrder($cardId, $orderTotal, $customer)
    {
        $voltStates = (bool) Cfg::get('VOLT_CUSTOM_STATE');
        $state = $voltStates ? Cfg::get(Config::VOLT_PENDING) : Cfg::get('PS_OS_BANKWIRE');
        $this->module->validateOrder(
            (int) $cardId,
            (int) $state,
            $orderTotal,
            $this->module->displayName,
            null,
            [],
            (int) $this->context->currency->id,
            $customer->isGuest() ? 0 : 1,
            $customer->secure_key
        );
    }
}
