<?php

declare(strict_types=1);

namespace Volt\Tests;

use Module;
use PHPUnit\Framework\TestCase;
use Volt\Adapter\ConfigurationAdapter;
use Volt\HookDispatcher;

class HookDispatcherTest extends TestCase
{
    private $module;
    private $configuration;
    private $hookDispatcher;

    protected function setUp(): void
    {
        $this->module = Module::getInstanceByName('volt');
        $this->assertEquals('volt', $this->module->name);
        $this->configuration = new ConfigurationAdapter(1);

        $this->hookDispatcher = new HookDispatcher($this->module, $this->configuration);
    }

    public function testGetAvailableHooks()
    {
        $this->assertCount(3, $this->hookDispatcher->getAvailableHooks());
        $this->assertEquals(
            [
                'actionFrontControllerSetMedia',
                'paymentOptions',
                'displayPayment',
            ],
            $this->hookDispatcher->getAvailableHooks()
        );
    }

    public function testDispatchReturnTrue()
    {
        $given = $this->hookDispatcher->dispatch('displayPayment', []);
        $this->assertEquals('', $given);
    }

    public function testDispatchReturnTrues()
    {
        $given = $this->module->__call('actionFrontControllerSetMedia', []);

        $this->assertEquals('', $given);
    }

    public function testDispatchReturnFalse()
    {
        $given = $this->hookDispatcher->dispatch('displayPayment2', []);
        $this->assertEquals('', $given);
    }
}
