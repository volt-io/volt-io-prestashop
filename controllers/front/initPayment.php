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

use Configuration as Cfg;
use Volt\Config\Config;
use Volt\Service\OrderData;
use Volt\Util\Helper;

if (!defined('_PS_VERSION_')) {
    exit;
}

class VoltInitPaymentModuleFrontController extends ModuleFrontController
{
    public $ajax;
    private $transitionRepository;
    public function __construct()
    {
        parent::__construct();
    }

    public function init()
    {
        parent::init();
        $this->transitionRepository = $this->get('volt.repository.transaction');
    }

    /**
     * @throws PrestaShopException
     */
    public function initContent()
    {
        parent::initContent();
        $ajax = true;

        if (Tools::getValue('action') === 'initPayment') {
            $this->initPayment();
        } elseif (Tools::getValue('action') === 'createTransaction') {
            $this->initTransaction();
        }

        exit;
    }

    private function getOrderData($context)
    {
        $invoiceAddressId = $context->cart->id_address_invoice;
        $customerId = $context->customer->id;

        return new OrderData(
            new AddressCore($invoiceAddressId),
            new Customer($customerId),
            $context
        );
    }

    private function createOrderData(): array
    {
        $context = $this->module->getContext();
        $this->module->debug(new AddressCore($context->cart->id_address_invoice), 'json');
        return $this->getOrderData($context)->getData($context->cart);
    }

    private function initPayment(): void
    {
        $pay = $this->module->api->request(
            'POST',
            'dropin-payments',
            $this->createOrderData(),
            true
        );

        $this->ajaxRender(json_encode($pay));
    }

    public function initTransaction()
    {
        $paymentId = Tools::getValue('paymentId');

        if (!$paymentId) {
            return false;
        }

        try {
            if (!$this->transitionRepository->isTransactionExists($paymentId)) {
                $this->transitionRepository->initTransaction($paymentId);
            }
        } catch (\Exception $exception) {
            \PrestaShopLogger::addLog($exception, 3);
        }

        $context = $this->module->getContext();
        $cart = $context->cart;
        $customer = $context->customer;
        $this->createOrder($cart, $customer, $paymentId);

        return $this->ajaxRender(json_encode(['status' => true]));
    }

    /**
     * Create order
     *
     * @param $cart
     * @param $customer
     * @param $paymentId
     *
     * @return void
     */
    private function createOrder($cart, $customer, $paymentId): void
    {
        $orderTotal = (float) $cart->getOrderTotal();
        $state = 'PENDING';

        if (
            \Validate::isLoadedObject($cart) &&
            !$cart->OrderExists()
        ) {
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
                    Helper::createCrc($cart, $customer),
                    $paymentId,
                    $this->module->currentOrder,
                    $cart,
                    $state
                );
            }
        }
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
