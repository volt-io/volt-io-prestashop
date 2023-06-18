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

namespace Volt\Hook;

use Volt\Service\RefundOrder;

class Admin extends AbstractHook
{
    private $_errors = [];
    private $_success = [];

    public const HOOK_LIST = [
        'displayAdminOrderMainBottom',
    ];

    private $transactionRepository;
    private $refundRepository;

    public function hookDisplayAdminOrderMainBottom($params)
    {
        $order = new \Order($params['id_order']);
        $output = '';

        if ($order->module !== 'volt') {
            return $output;
        }

        $this->transactionRepository = $this->module->getService('volt.repository.transaction');
        $this->refundRepository = $this->module->getService('volt.repository.refund');
        $transaction = $this->transactionRepository->getOrderByOrderId($params['id_order'])[0];

        $refundType = \Tools::getValue('volt_refund_type', 'full');
        $refundValue = (float) str_replace(',', '.', \Tools::getValue('volt_refund_amount'));
        $refundAmount = $refundType === 'full'
            ? $order->total_paid
            : $refundValue;

        $isRefundable = $this->isRefundable($transaction);

        $this->getRefundOrder(
            $transaction,
            $refundType,
            $refundAmount,
            $order
        );

        $allRefunds = $this->refundRepository->getRefundByOrderId($params['id_order']) ?? false;

        $this->context->smarty->assign([
            'all_refunds' => $allRefunds,
            'test' => $order,
            'id' => $transaction,
            'is_refundable' => $isRefundable,
            'refund_type' => $refundType,
            'refund_amount' => $refundAmount,
            'refund_all' => number_format(
                (float) $order->total_paid,
                2,
                '.',
                ''
            ),
            '_errors' => $this->_errors,
            '_success' => $this->_success,
        ]);

        return $this->module->fetch('module:volt/views/templates/admin/order_info.tpl');
    }

    public function getRefundOrder($transaction, $refundType, $amount, $order)
    {
        if (\Tools::getValue('volt-refund')) {
            $totalPaid = (int) ($order->total_paid * 100);
            $totalAmount = (int) ($amount * 100);

            if ($totalAmount > $totalPaid) {
                $this->_errors[] = $this->module->l('The refund amount you entered is greater than order amount.');
            } else {
                $refund = false;

                try {
                    $test = new RefundOrder($this->module);
                    $refund = $test->requestRefund($transaction['crc'], $refundType, $totalAmount);

                    $this->module->debug($refund, 'REFUND');
                } catch (\Exception $exception) {
                    $this->_errors[] = $this->module->l('Refund error: ') . $exception;
                    \PrestaShopLogger::addLog($exception, 3);
                }

                if (isset($refund->id)) {
                    // Create refund query
                    $this->refundRepository->create(
                        $transaction['order_id'],
                        $refund->id,
                        '---',
                        $totalAmount,
                        $transaction['currency'],
                        'PENDING'
                    );
                } elseif (isset($refund->exception)) {
                    if (isset($refund->exception->code)) {
                        $this->_errors[] = $this->module->l('Couldn\'t create a refund for amount. Balance Exceeded');
                    }
                }

                if (empty($this->_errors)) {
                    $this->_success[] = $this->module->l('Success refund');
                }
            }
        }
    }

    public function isRefundable($transaction)
    {
        $this->module->debug($transaction, 'refundTransaction');

        $refundSevice = new RefundOrder($this->module);
        return $refundSevice->checkIsRefundActive($transaction['crc']);
    }
}
