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

class VoltNotificationsModuleFrontController extends ModuleFrontController
{
    public $ssl = true;
    private $transitionRepository;
    private $refundRepository;
    public $display_column_left = false;

    /**
     * @throws PrestaShopDatabaseException
     * @throws PrestaShopException
     */
    public function display()
    {
        $this->transitionRepository = $this->get('volt.repository.transaction');
        $this->refundRepository = $this->get('volt.repository.refund');

        $signed = null;
        $timed = null;
        $ver = null;

        foreach (getallheaders() as $name => $value) {
            if ($name === 'User-Agent') {
                $ver = $value;
            }
            if ($name === 'x-volt-timed') {
                $timed = $value;
            }
            if ($name === 'x-volt-signed') {
                $signed = $value;
            }

            $entityBody = \Tools::file_get_contents('php://input');
            $body = json_decode($entityBody, true);
        }

        header('Content-Type: application/json');
        header('Accept: */*');
        header('Accept-Encoding: gzip');
        header('x-volt-signed: ' . $signed);
        header('x-volt-timed: ' . $timed);
        header('User-Agent: ' . $ver);

        $entityBody = \Tools::file_get_contents('php://input');
        $body = json_decode($entityBody, true);

        $paymentId = $body['payment'] ?? null;
        $refundId = $body['refund'] ?? null;
        $state = $body['status'] ?? null;

        if ($paymentId && $state && $body) {

            if(!$this->checkOrderVoltApi($paymentId)) {
                return;
            }

            $this->changeOrderState($paymentId, $state, $body);

            // Refund
            if ($refundId) {
                $this->changeRefundState($refundId, $paymentId, $state, $body);
                \PrestaShopLogger::addLog('refund', 3);
            }
        }

        $this->ajaxRender(json_encode([], JSON_FORCE_OBJECT));
    }

    private function checkOrderVoltApi($paymentId): bool
    {
        try {
            $order = $this->module->api->request('GET', 'payments/' . urldecode($paymentId));
            if ($order->id !== $paymentId) {
                \PrestaShopLogger::addLog('Volt - Error: Wrong payment id notification' , 3);
                return false;
            }
        } catch (\Exception $exception) {
            \PrestaShopLogger::addLog($exception, 1);
            return false;
        }

        return true;
    }

    private function changeOrderState(string $paymentId, string $state, $body)
    {
        // get orderby payment id
        $orderState = $this->transitionRepository->getOrderStateByPaymentId($paymentId);

        if (
            $orderState === 'PENDING'
            || $orderState === 'FAILURE'
            || $orderState === 'FAILED'
            || $orderState === 'SUCCESS'
            || $orderState === 'COMPLETED'
            || $orderState === 'REFUND_CONFIRMED'
        ) {
            $timestamp = $body['timestamp'] ?? null;
            $detailedStatus = $body['detailedStatus'] ?? null;

            // Update status
            $this->transitionRepository->updateTransactionStatusByPaymentId(
                $paymentId,
                $state,
                $timestamp,
                $detailedStatus
            );
            $orderId = $this->transitionRepository->getOrderIdByPaymentId($paymentId);
            $order = new \Order($orderId);

            if ($state === 'RECEIVED' || $state === 'REFUND_CONFIRMED') {
                $refund = $state === 'REFUND_CONFIRMED';
                $error = false;
                $stateService = $this->module->getService('volt.handler.order_state');
                $stateService->changeOrdersState($order, $paymentId, $error, $refund);
            }
        }
    }

    private function changeRefundState(string $refundId, string $paymentId, string $state, $body)
    {
        $refundState = $this->refundRepository->getOrderStateByRefundId($refundId);

        if (
            $refundState === 'PENDING'
            || $refundState === 'FAILURE'
            || $refundState === 'SUCCESS'
        ) {
            // Update status
            $this->refundRepository->updateTransactionStatusByRefundId(
                $refundId,
                $state
            );

            $orderId = $this->refundRepository->getOrderIdByRefundId($refundId);
            $order = new \Order($orderId);

            if ($state === 'REFUND_CONFIRMED') {
                $stateService = $this->module->getService('volt.handler.order_state');
                $stateService->changeOrdersState($order, $paymentId, false, true);
            }
        }
    }
}
