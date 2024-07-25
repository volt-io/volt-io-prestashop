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

class VoltScheduleModuleFrontController extends ModuleFrontController
{
    public $ssl = true;
    public $display_column_left = false;

    /**
     * @throws PrestaShopDatabaseException
     * @throws PrestaShopException
     */
    public function initContent()
    {
        parent::initContent();

        if (Tools::getValue('action') === 'schedule') {
            $this->schedule();
        }
    }

    private function schedule()
    {
        $date = new DateTime('now');
        $dateFormat = $date->format('Y-m-d H:i:s');

        $sql = new DbQuery();
        $sql->select('crc, order_id');
        $sql->from('volt_transactions', 'v');
        $sql->where('v.date_upd < DATE_ADD("' . $dateFormat . '", INTERVAL 3 HOUR)');
        $sql->where('v.status = "FAILED"');
        $repo = Db::getInstance()->executeS($sql);

        foreach ($repo as $r) {
            if (isset($r['crc'], $r['order_id'])) {
                $this->scheduleChangedTransactionStatus($r['crc'], (int) $r['order_id']);
            }
        }
    }

    private function scheduleChangedTransactionStatus(string $paymentId, int $orderId)
    {
        $transactionRepository = $this->module->getService('volt.repository.transaction');
        $transactionRepository->updateTransactionStatusByPaymentId($paymentId, 'FAILED');

        // Update order state
        $order = new Order($orderId);
        $stateService = $this->module->getService('volt.handler.order_state');
        $stateService->changeOrdersState($order, $paymentId, false, false, true);
    }
}
