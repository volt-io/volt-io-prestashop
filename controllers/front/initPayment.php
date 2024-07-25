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
if (!defined('_PS_VERSION_')) {
    exit;
}

use Configuration as Cfg;
use Volt\Config\Config;
use Volt\Service\OrderData;
use Volt\Util\Helper;

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

    public function initContent()
    {
        parent::initContent();
        $ajax = true;
        $context = $this->module->getContext();

        if (Tools::getValue('action') === 'initPayment') {
            $this->removePaymentId();
            $this->initPayment();
        } elseif (Tools::getValue('action') === 'createTransaction') {
            $customer = $context->customer;
            $paymentId = trim(Tools::getValue('paymentId'));
            $this->initTransaction($paymentId);

            $this->createOrder(
                $context->cart,
                $customer,
                $paymentId
            );
        } elseif (Tools::getValue('action') === 'updateTransaction') {
            $amount = (int) ($context->cart->getOrderTotal(true, Cart::BOTH) * 100);
            $paymentId = $this->getPaymentId();
            $patch = '';

            if ($paymentId) {
                $patch = $this->module->api->request(
                    'PATCH',
                    'dropin/' . $paymentId,
                    [
                        'amount' => $amount,
                    ],
                    true
                );
            }

            header('Content-Type: application/json');
            $this->ajaxDie(json_encode($patch));
        }
        exit;
    }

    private function savePaymentId($paymentId)
    {
        $encryptedData = $paymentId;
        $cookie = new Cookie('volt_pid');
        $cookie->volt_pid = $encryptedData;
        $cookie->write();
    }

    private function getPaymentId()
    {
        $cookie = new Cookie('volt_pid');
        if (isset($cookie->volt_pid)) {
            return $cookie->volt_pid;
        }
        return null;
    }

    private function removePaymentId()
    {
        $cookie = new Cookie('volt_pid');
        $cookie->volt_pid = '';
        $cookie->logout();
        $cookie->write();
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
        return $this->getOrderData($context)->getData($context->cart);
    }

    private function initPayment(): void
    {
        $pay = $this->module->api->request(
            'POST',
            'dropin',
            $this->createOrderData(),
            true
        );
        $payData = json_encode($pay);
        $this->savePaymentId($pay->id);

        $this->ajaxRender($payData);
    }

    public function initTransaction($paymentId)
    {
        if (!$paymentId) {
            return false;
        }

        try {
            if (!$this->transitionRepository->isTransactionExists($paymentId)) {
                $this->transitionRepository->initTransaction($paymentId);
            }
        } catch (Exception $exception) {
            PrestaShopLogger::addLog($exception, 3);
        }

        return $this->ajaxRender(json_encode(['status' => true]));
    }

    /**
     * Create order
     *
     * @param $cart
     * @param $customer
     * @param $paymentId
     *
     * @return mixed
     */
    private function createOrder($cart, $customer, $paymentId): void
    {
        $orderTotal = $cart->getOrderTotal(true, Cart::BOTH);
        $state = 'PENDING';

        if (
            Validate::isLoadedObject($cart)
            && !$cart->OrderExists()
        ) {
            $this->createShopOrder($cart->id, $orderTotal, $customer);

            if (isset($this->module->currentOrder) && !empty($this->module->currentOrder)) {
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

    private function createShopOrder($cardId, $orderTotal, $customer)
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
