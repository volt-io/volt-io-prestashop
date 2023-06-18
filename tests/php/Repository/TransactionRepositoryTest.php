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
use Volt\Entity\VoltTransactions;
use Volt\Repository\TransactionRepository;

class TransactionRepositoryTest extends TestCase
{

    private $entityManager;
    private $transactionRepository;

    public const TABLE = 'volt_transactions';
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

        $this->transactionRepository = $kernel1->getContainer()->get("volt.repository.transaction");

        $this->testInitTransaction();

    }

    public function testInitTransaction()
    {
        $given = $this->transactionRepository->initTransaction(
            '999-999'
        );

        $this->assertNull($given);
    }

    public function testShouldUpdateTransactionStatusByPaymentIdReturnNull()
    {
        $given = $this->transactionRepository->updateTransactionStatusByPaymentId('999-999', 'SUCCESS');

        $this->assertNull($given);
    }

    public function testShouldUpdateTransactionOrderByPaymentIdReturnNull()
    {

        $cart = $this->getMockBuilder(\Cart::class)
            ->disableOriginalConstructor()
            ->enableOriginalClone()
            ->setMethodsExcept([])
            ->getMock();

        $cart->id_currency = '1';

        //        $cart->method('get')->with('id_currency')->willReturn('1');
        $cart->method('getOrderTotal')->willReturn(true);

        $given = $this->transactionRepository->updateTransactionOrderByPaymentId(
            'reference',
            '999-999',
            999,
            $cart,
            'SUCCESS'
        );

        $this->assertNull($given);
    }

    public function testShouldGetTransactionByPaymentIdReturnArray()
    {
        $given = $this->transactionRepository->getTransactionByPaymentId('999-999');

        $this->assertIsArray($given);
        $this->assertNotEmpty($given);
    }

    public function testShouldGetTransactionByPaymentIdReturnEmptyArray()
    {
        $given = $this->transactionRepository->getTransactionByPaymentId('0');

        $this->assertIsArray($given);
        $this->assertEmpty($given);
    }

    public function testShouldGetOrderStateByPaymentIdReturnString()
    {
        $given = $this->transactionRepository->getOrderStateByPaymentId('999-999');

        $this->assertIsString($given);
        $this->assertSame('SUCCESS', $given);
    }

    public function testShouldIsTransactionExistsReturnTrue()
    {
        $given = $this->transactionRepository->isTransactionExists('999-999');

        $this->assertTrue($given);
    }

    public function testShouldIsTransactionExistsReturnFalse()
    {
        $given = $this->transactionRepository->isTransactionExists('0');

        $this->assertFalse($given);
    }

    public function testShouldGetOrderIdByReferenceReturnString()
    {
        $given = $this->transactionRepository->getOrderIdByReference('reference');

        $this->assertIsString($given);
        $this->assertNotEmpty($given);
    }

    public function testShouldGetOrderIdByReferenceReturnFalse()
    {
        $given = $this->transactionRepository->getOrderIdByReference('refff');

        $this->assertFalse($given);
    }

    public function testShouldGetOrderIdByPaymentIdReturnString()
    {
        $given = $this->transactionRepository->getOrderIdByPaymentId('999-999');

        $this->assertIsString($given);
        $this->assertNotEmpty($given);
    }

    public function testShouldGetOrderByOrderIdReturnEmptyArray()
    {
        $given = $this->transactionRepository->getOrderByOrderId(0);

        $this->assertIsArray($given);
        $this->assertEmpty($given);
    }

    public function testShouldGetTransactionByPaymentId()
    {
        $given = $this->transactionRepository->getOrderStatusHistory(999);

        $this->assertIsArray($given);
    }

    protected function tearDown()
    :void
    {
        parent::tearDown();

        $this->entityManager->close();
        $this->entityManager = null;
    }

}
