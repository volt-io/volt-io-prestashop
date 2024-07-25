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

namespace Volt\Repository;

if (!defined('_PS_VERSION_')) {
    exit;
}

use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManager;
use Volt\Entity\VoltTransactions;

class TransactionRepository
{
    public const TABLE = 'volt_transactions';

    /**
     * @var Connection
     */
    private $connection;

    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var string
     */
    private $dbPrefix;

    /**
     * @param Connection $connection
     * @param EntityManager $entityManager
     * @param string $dbPrefix
     */
    public function __construct(
        Connection $connection,
        EntityManager $entityManager,
        string $dbPrefix
    ) {
        $this->connection = $connection;
        $this->entityManager = $entityManager;
        $this->dbPrefix = $dbPrefix;
    }

    public function initTransaction($paymentId): void
    {
        $transaction = new VoltTransactions();
        $transaction->setCrc($paymentId);

        $transaction->setStatus('PENDING');

        $this->entityManager->persist($transaction);
        $this->entityManager->flush();
    }

    public function getTransactionByPaymentId(string $paymentId)
    {
        $qb = $this->connection->createQueryBuilder();
        $qb
            ->addSelect('*')
            ->from($this->dbPrefix . self::TABLE, 'v')
            ->andWhere('v.crc = :crc')
            ->setParameter('crc', $paymentId);

        return $qb->execute()->fetchAll();
    }
    public function isTransactionExists($paymentId)
    {
        return (bool) $this->getTransactionByPaymentId($paymentId);
    }

    public function getOrderIdByPaymentId($paymentId)
    {
        $qb = $this->connection->createQueryBuilder();
        $qb
            ->addSelect('order_id')
            ->from($this->dbPrefix . self::TABLE, 'v')
            ->andWhere('v.crc = :crc')
            ->setParameter('crc', $paymentId);

        return $qb->execute()->fetchColumn();
    }

    public function getOrderIdByReference($reference)
    {
        $qb = $this->connection->createQueryBuilder();
        $qb
            ->addSelect('order_id')
            ->from($this->dbPrefix . self::TABLE, 'v')
            ->andWhere('v.reference = :reference')
            ->setParameter('reference', $reference);

        return $qb->execute()->fetchColumn();
    }

    public function getOrderByOrderId($orderId)
    {
        $qb = $this->connection->createQueryBuilder();
        $qb
            ->addSelect('*')
            ->from($this->dbPrefix . self::TABLE, 'v')
            ->andWhere('v.order_id = :orderId')
            ->setParameter('orderId', $orderId);

        return $qb->execute()->fetchAll();
    }

    public function getOrderStateByPaymentId($paymentId)
    {
        $qb = $this->connection->createQueryBuilder();
        $qb
            ->addSelect('status')
            ->from($this->dbPrefix . self::TABLE, 'v')
            ->andWhere('v.crc = :crc')
            ->setParameter('crc', $paymentId);

        return $qb->execute()->fetchColumn();
    }

    public function getOrderStatusHistory($orderId): array
    {
        $qb = $this->connection->createQueryBuilder();
        $qb
            ->addSelect('id_order_state')
            ->from($this->dbPrefix . 'order_history', 'v')
            ->andWhere('v.id_order = :orderId')
            ->setParameter('orderId', $orderId);

        return $qb->execute()->fetchAll();
    }

    public function updateTransactionOrderByPaymentId(
        string $reference,
        string $crc,
        int $orderId,
        $cart,
        $state
    ): void {
        $date = new \DateTime('now');
        $formatDate = $date->format('Y-m-d H:i:s');

        $currency = \Currency::getCurrency($cart->id_currency);
        $amount = (int) ($cart->getOrderTotal(true, \Cart::BOTH) * 100);

        $transactionState = $state ?? 'PENDING';

        $qb = $this->connection->createQueryBuilder();
        $qb
            ->update($this->dbPrefix . self::TABLE)

            ->set('order_id', ':orderId')
            ->set('amount', ':amount')
            ->set('currency', ':currency')
            ->set('status', ':status')
            ->set('reference', ':reference')
            ->set('date_upd', ':date_upd')
            ->andWhere('crc = :crc')
            ->setParameter('orderId', $orderId)
            ->setParameter('amount', $amount)
            ->setParameter('currency', $currency['iso_code'])
            ->setParameter('status', $transactionState)
            ->setParameter('crc', $crc)
            ->setParameter('reference', $reference)
            ->setParameter('date_upd', $formatDate)
        ;

        $qb->execute($qb);
    }

    public function updateTransactionStatusByPaymentId(
        string $paymentId,
        string $state,
        $date,
        $statusDetail = null
    ): void {
        $qb = $this->connection->createQueryBuilder();
        $qb
            ->update($this->dbPrefix . self::TABLE)
            ->set('status', ':status')
            ->set('date_upd', ':date')
            ->andWhere('crc = :crc')
            ->setParameter('status', $state)
            ->setParameter('date', $date)
            ->setParameter('crc', $paymentId)
        ;

        if ($statusDetail) {
            $qb->set('status_detail', ':status_detail');
            $qb->setParameter('status_detail', $statusDetail);
        }

        $qb->execute($qb);
    }
}
