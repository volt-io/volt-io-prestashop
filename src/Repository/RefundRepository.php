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
use Volt\Entity\VoltRefunds;

class RefundRepository
{
    public const TABLE = 'volt_refunds';

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

    public function create(
        $orderId,
        string $refundId,
        $reference,
        $amount,
        string $currency,
        string $status
    ): void {
        $transaction = new VoltRefunds();
        $transaction->setOrderId($orderId);
        $transaction->setReference($reference);
        $transaction->setCrc($refundId);
        $transaction->setAmount($amount);
        $transaction->setCurrency($currency);
        $transaction->setStatus($status);

        $this->entityManager->persist($transaction);
        $this->entityManager->flush();
    }

    public function getRefundByOrderId(int $orderId)
    {
        $qb = $this->connection->createQueryBuilder();
        $qb
            ->addSelect('*')
            ->from($this->dbPrefix . self::TABLE, 'v')
            ->andWhere('v.order_id = :order_id')
            ->setParameter('order_id', $orderId);

        return $qb->execute()->fetchAll();
    }

    public function getOrderIdByRefundId($refundId)
    {
        $qb = $this->connection->createQueryBuilder();
        $qb
            ->addSelect('order_id')
            ->from($this->dbPrefix . self::TABLE, 'v')
            ->andWhere('v.crc = :crc')
            ->setParameter('crc', $refundId);

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

    public function getOrderStateByRefundId($paymentId)
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

    public function updateTransactionStatusByRefundId(
        string $crc,
        string $state
    ): void {
        $transactionState = $state;
        $qb = $this->connection->createQueryBuilder();
        $qb
            ->update($this->dbPrefix . self::TABLE)
            ->set('status', ':status')
            ->andWhere('crc = :crc')
            ->setParameter('status', $transactionState)
            ->setParameter('crc', $crc);
        $qb->execute($qb);
    }
}
