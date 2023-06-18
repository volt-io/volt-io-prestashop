<?php

namespace Volt\Tests\Hook;

use OrderHistory;
use Order;
use Tools;
use Context;
use Db;
use PHPUnit\Framework\TestCase;
use Module;
use Currency;
use PrestaShop\PrestaShop\Core\Payment\PaymentOption;
use Symfony\Component\Translation\TranslatorInterface;

class AdminTest extends TestCase
{
    private $module;

    protected function setUp(): void
    {
        $this->module = Module::getInstanceByName('volt');
    }
}
