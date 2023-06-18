<?php

namespace Volt\Tests\Hook;

use Configuration as Config;
use OrderHistory;
use Order;
use Tools;
use Context;
use Db;
use PHPUnit\Framework\TestCase;
use Module;
use Currency;

class AbstractHookTest extends TestCase
{
    private $module;
    private $context;

    protected function setUp(): void
    {
        $this->module = Module::getInstanceByName('volt');
        $this->context = $this->module->getContext();
    }
}
