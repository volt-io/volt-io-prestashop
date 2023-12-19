<?php

declare(strict_types=1);

namespace Volt\Tests\Service;

use Module;
use PHPUnit\Framework\TestCase;
use Volt\Service\RefundOrder;

class RefundOrderTest extends TestCase
{
    private $refundOrder;

    protected function setUp(): void
    {
        $this->module = Module::getInstanceByName('volt');
        $this->refundOrder = new RefundOrder($this->module);
    }

    public function testShouldCheckIsRefundActiveReturnTrue()
    {
        $paymentId = '888';
        $given = $this->refundOrder->checkIsRefundActive($paymentId);

        $this->assertIsBool($given);
        $this->assertTrue($given);
    }

    public function testShouldCheckIsRefundActiveReturnFalse()
    {
        $paymentId = '0';
        $given = $this->refundOrder->checkIsRefundActive($paymentId);

        $this->assertIsBool($given);
        $this->assertFalse($given);
    }

    public function testShouldCheckIsRefundActiveReturnException()
    {
        $this->expectException(\Exception::class);

        $paymentId = '888';
        $given = $this->refundOrder->checkIsRefundActive($paymentId);

        throw new \Exception('Error in the method of checking the availability of the order return option');
        $this->assertSame('Error in the method of checking the availability of the order return option', $given);
    }
}
