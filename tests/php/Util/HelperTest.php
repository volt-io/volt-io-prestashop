<?php

declare(strict_types = 1);

namespace Volt\Tests\Util;

use Context;
use Db;
use PHPUnit\Framework\TestCase;
use Volt\Util\Helper;
use Module;

class HelperTest extends TestCase
{
    private $module;
    private $context;

    protected function setUp()
    :void
    {
        $this->module = Module::getInstanceByName('volt');
        $this->context = Context::getContext();
    }

    public function testShouldGetFields()
    {
        $fields = Helper::getFields();

        $expected = [
            'VOLT_ENV',
            'VOLT_ENV_SWITCH',
            'VOLT_PROD_CLIENT_ID',
            'VOLT_PROD_CLIENT_SECRET',
            'VOLT_PROD_NOTIFICATION_SECRET',
            'VOLT_PROD_USERNAME',
            'VOLT_PROD_PASSWORD',

            'VOLT_SANDBOX_CLIENT_ID',
            'VOLT_SANDBOX_CLIENT_SECRET',
            'VOLT_SANDBOX_NOTIFICATION_SECRET',
            'VOLT_SANDBOX_USERNAME',
            'VOLT_SANDBOX_PASSWORD',

            'VOLT_CUSTOM_STATE',
            'VOLT_PENDING_STATE_ID',
            'VOLT_NOT_PAID_STATE_ID',
            'VOLT_SUCCESS_STATE_ID',
            'VOLT_FAILURE_STATE_ID'
        ];

        $this->assertEquals($expected, $fields);
        $this->assertIsArray($fields);
    }

    public function testShouldGetStyleFields()
    {
        $fields = Helper::getStyleFields();

        $expected = [
            'VOLT_COLOR',
            'VOLT_BG_COLOR',

            'VOLT_INPUT_COLOR',
            'VOLT_INPUT_BG_COLOR',
            'VOLT_INPUT_BORDER_COLOR',
            'VOLT_INPUT_BORDER_RADIUS',

            'VOLT_BTN_COLOR',
            'VOLT_BTN_BG_COLOR',
            'VOLT_BTN_BORDER_COLOR',
            'VOLT_BTN_BORDER_RADIUS',

            'VOLT_LINK_COLOR',
            'VOLT_ICON_COLOR',
        ];

        $this->assertEquals($expected, $fields);
        $this->assertIsArray($fields);
    }

    public function testShouldGetClientIp()
    {
        $ip = Helper::getClientIp();
        $this->assertEquals('127.0.0.1', $ip);
    }

    public function testShouldGetClientIpServer()
    {
        $ip = Helper::getClientIpServer();
        $this->assertEquals('127.0.0.1', $ip);
    }

    public function testShouldGetCrc()
    {

        $cart = $this->getMockBuilder(\Cart::class)
            ->disableOriginalConstructor()
            ->enableOriginalClone()
            ->setMethodsExcept([])
            ->getMock();

        $cart->id = '1';

        $customer = $this->getMockBuilder(\Customer::class)
            ->disableOriginalConstructor()
            ->enableOriginalClone()
            ->setMethodsExcept([])
            ->getMock();

        $customer->secure_key = '123342324';

        $actual = Helper::createCrc($cart, $customer);

        $this->assertIsString($actual);
        $this->assertNotEmpty($actual);
    }
}
