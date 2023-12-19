<?php

namespace Volt\Tests\Hook;

use Module;
use PHPUnit\Framework\TestCase;

class AdminTest extends TestCase
{
    private $module;

    protected function setUp(): void
    {
        $this->module = Module::getInstanceByName('volt');
    }
}
