<?php

namespace Volt\Tests\Hook;

use Module;
use PHPUnit\Framework\TestCase;

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
