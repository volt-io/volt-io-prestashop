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

if (!defined('_PS_VERSION_')) {
    exit;
}

class VoltSuccessModuleFrontController extends ModuleFrontController
{
    public $ssl = true;
    public $display_column_left = false;

    /**
     * @throws PrestaShopDatabaseException
     * @throws PrestaShopException
     */
    public function display()
    {
        $reference = Tools::getValue('reference');
        if ($reference) {
            $transitionRepository = $this->get('volt.repository.transaction');
            $orderId = $transitionRepository->getOrderIdByReference($reference);
            $this->redirectConfirmation($orderId);
        }
    }

    private function redirectConfirmation($orderId)
    {
        $order = new Order($orderId);
        $cart = new Cart($order->id_cart);
        $customer = new Customer($order->id_customer);
        Tools::redirect(
            'index.php?controller=order-confirmation&id_cart=' . (int) $cart->id . '&id_module=' .
            (int) $this->module->id . '&id_order=' . $order->id . '&key=' . $customer->secure_key
        );
    }
}
