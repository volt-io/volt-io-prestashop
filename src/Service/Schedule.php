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
class Schedule
{
    /**
     * @var \Module
     */
    private $module;

    public function __construct(
        \Module $module
    ) {
        $this->module = $module;
    }

    public function runSchedule()
    {
        $failedTransactions = $this->getFailedTransactions();
        $rejectedTransactions = $this->getRejectedTransactions();
        $pendingTransactions = $this->getPendingTransactions();

        $this->precessAll($pendingTransactions);
        $this->precessAll($failedTransactions);
        $this->precessAll($rejectedTransactions);
    }

    private function precessAll($transactions)
    {
        if ($transactions) {
            foreach ($transactions as $r) {
                if (isset($r['crc'], $r['order_id'])) {
                    $this->changeTransactionStatus($r['crc'], (int) $r['order_id']);
                }
            }
        }
    }

    private function getPendingTransactions()
    {
        $date = new \DateTime('now');
        $dateFormat = $date->format('Y-m-d H:i:s');

        $sql = new \DbQuery();
        $sql->select('crc, order_id, status, status_detail');
        $sql->from('volt_transactions', 'v');
        $sql->where('v.date_upd < DATE_ADD("' . $dateFormat . '", INTERVAL 7 DAY)');
        $sql->where('v.status = "PENDING"');

        return \Db::getInstance()->executeS($sql);
    }

    private function getRejectedTransactions()
    {
        $date = new \DateTime('now');
        $dateFormat = $date->format('Y-m-d H:i:s');

        $sql = new \DbQuery();
        $sql->select('crc, order_id, status, status_detail');
        $sql->from('volt_transactions', 'v');
        $sql->where('v.date_upd < DATE_ADD("' . $dateFormat . '", INTERVAL 3 HOUR)');
        $sql->where('v.status = "FAILED"');
        $sql->where('v.status_detail = "ABANDONED_BY_USER"');

        return \Db::getInstance()->executeS($sql);
    }

    private function getFailedTransactions()
    {
        $sql = new \DbQuery();
        $sql->select('crc, order_id, status, status_detail');
        $sql->from('volt_transactions', 'v');
        $sql->where('v.status = "FAILED"');
        $sql->where('v.status_detail != "ABANDONED_BY_USER"');

        return \Db::getInstance()->executeS($sql);
    }

    private function changeTransactionStatus(string $paymentId, int $orderId)
    {
        $date = new \DateTime('now');
        $dateFormat = $date->format('Y-m-d H:i:s');
        $transactionRepository = $this->module->getService('volt.repository.transaction');
        $transactionRepository->updateTransactionStatusByPaymentId($paymentId, 'NOT PAID', $dateFormat);
        $this->changeOrderStatus($orderId, $paymentId);
    }

    private function changeOrderStatus($orderId, $paymentId)
    {
        $order = new \Order($orderId);
        $stateService = $this->module->getService('volt.handler.order_state');
        $stateService->changeOrdersState($order, $paymentId, false, false, true);
    }
}
