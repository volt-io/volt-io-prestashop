<?php

declare(strict_types=1);

namespace Volt\Tests\Service;

use PHPUnit\Framework\TestCase;
use Volt\Service\OrderData;

class OrderDataTest extends TestCase
{
    protected function setUp(): void
    {
        $address = $this->getMockBuilder(\AddressCore::class)
            ->disableOriginalConstructor()
            ->enableOriginalClone()
            ->setMethodsExcept([])
            ->getMock();

        $customer = $this->getMockBuilder(\Customer::class)
            ->disableOriginalConstructor()
            ->enableOriginalClone()
            ->setMethodsExcept([])
            ->getMock();

        $context = $this->getMockBuilder(\Context::class)
            ->disableOriginalConstructor()
            ->enableOriginalClone()
            ->setMethodsExcept([])
            ->getMock();

        $link = $this->getMockBuilder(\Link::class)
            ->disableOriginalConstructor()
            ->enableOriginalClone()
            ->onlyMethods(['getModuleLink'])
            ->getMock();

        $link->expects($this->any())->method('getModuleLink')->willReturn('test');

        $context->cart = ['id' => '1'];
        $context->customer = (object) ['id' => 11, 'secure_key' => 123123];
        $context->currency = ['iso_code' => 'USD'];
        $context->link = $link;

        $this->orderData = new OrderData(
            $address,
            $customer,
            $context
        );
    }

    public function testShouldGetReferenceNumber()
    {
        $cart = $this->getMockBuilder(\Cart::class)
            ->disableOriginalConstructor()
            ->getMock();

        $cart->id = '1';
        $given = $this->orderData->generateReference($cart);

        $this->assertIsString($given);
    }

    public function testGetDataArray()
    {
        $cart = $this->getMockBuilder(\Cart::class)
            ->disableOriginalConstructor()
            ->enableOriginalClone()
            ->onlyMethods(['getOrderTotal'])
            ->getMock();

        $cart->expects($this->any())->method('getOrderTotal')->willReturn(11);
        $cart->id = '1';

        $given = $this->orderData->getData($cart);
        $this->assertIsArray($given);
        $this->assertIsArray($given);
        $this->assertArrayHasKey('currencyCode', $given, "Array doesn't contains 'currencyCode'");
        $this->assertArrayHasKey('amount', $given, "Array doesn't contains 'amount'");
        $this->assertArrayHasKey('type', $given, "Array doesn't contains 'type'");
        $this->assertArrayHasKey('uniqueReference', $given, "Array doesn't contains 'uniqueReference'");
        $this->assertArrayHasKey('payer', $given, "Array doesn't contains 'payer'");
    }
}
