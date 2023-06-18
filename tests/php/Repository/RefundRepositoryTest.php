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
declare(strict_types = 1);

namespace Volt\Tests\Repository;

use PHPUnit\Framework\TestCase;
use Volt\Entity\VoltRefunds;
use Volt\Repository\RefundRepository;

class RefundRepositoryTest extends TestCase
{

    private $entityManager;
    private $refundRepository;

    public const TABLE = 'volt_refunds';
    public $dbPrefix = 'ps_';

    protected function setUp()
    :void
    {

        global $kernel;
        if ($kernel) {
            $kernel1 = $kernel;
        } // otherwise create it manually
        else {
            require_once _PS_ROOT_DIR_.'/app/AppKernel.php';
            $env = 'prod'; //_PS_MODE_DEV_ ? 'dev' : 'prod';
            $debug = false;//_PS_MODE_DEV_ ? true : false;
            $kernel1 = new \AppKernel($env, $debug);
            $kernel1->boot();
        }

        $this->entityManager = $kernel1->getContainer()->get('doctrine')
            ->getManager();

        $this->refundRepository = $kernel1->getContainer()->get("volt.repository.refund");
        $this->testCreateRefund();

    }

    public function testCreateRefund()
    {
        $given = $this->refundRepository->create(
            999,
            '888',
            'reference',
            '100',
            'PLN',
            'PENDING'
        );

        $this->assertNull($given);
    }

    public function testShouldGetRefundByOrderIdReturnArray()
    {
        $given = $this->refundRepository->getRefundByOrderId(999);

        $this->assertIsArray($given);
        $this->assertNotEmpty($given);
    }

    public function testShouldGetRefundByOrderIdReturnEmptyArray()
    {
        $given = $this->refundRepository->getRefundByOrderId(0);
        $this->assertEmpty($given);
        $this->assertIsArray($given);
    }

    public function testShouldGetOrderIdByRefundIdReturnString()
    {
        $given = $this->refundRepository->getOrderIdByRefundId('888');

        $this->assertEquals("999", $given);
        $this->assertIsString($given);
    }

    public function testShouldGetOrderIdByRefundIdReturnFalse()
    {
        $given = $this->refundRepository->getOrderIdByRefundId('0');

        $this->assertFalse($given);
    }

    public function testShouldGetOrderByOrderIdReturnArray()
    {
        $given = $this->refundRepository->getOrderByOrderId(999);

        $this->assertIsArray($given);
        $this->assertNotEmpty($given);
    }

    public function testShouldGetOrderByOrderIdReturnEmptyArray()
    {
        $given = $this->refundRepository->getOrderByOrderId(0);

        $this->assertIsArray($given);
        $this->assertEmpty($given);
    }

    public function testShouldGetOrderStateByRefundIdReturnString()
    {
        $given = $this->refundRepository->getOrderStateByRefundId('888');

        $this->assertIsString($given);
        $this->assertSame("SUCCESS", $given);
    }

    public function testShouldGetOrderStateByRefundIdReturnFalse()
    {
        $given = $this->refundRepository->getOrderStateByRefundId('0');

        $this->assertFalse($given);
        $this->assertEmpty($given);
    }

    public function testShouldGetOrderStatusHistoryReturnFalse()
    {
        $given = $this->refundRepository->getOrderStatusHistory(999);

        $this->assertIsArray($given);
    }

    public function testShouldUpdateTransactionStatusByRefundId()
    {
        $given = $this->refundRepository->updateTransactionStatusByRefundId('888', 'SUCCESS');

        $this->assertNull($given);
    }

    protected function tearDown()
    :void
    {
        parent::tearDown();

        $this->entityManager->close();
        $this->entityManager = null;
    }
}
