<?php

declare(strict_types = 1);

namespace Volt\Tests\Adapter;

use PHPUnit\Framework\TestCase;
use Volt;
use Volt\Adapter\ConfigurationAdapter;
use PrestaShop\PrestaShop\Adapter;
use Module;

class ConfigurationAdapterTest extends TestCase
{
    private $configurationAdapter;
    private $container;

    protected function setUp()
    :void
    {
        $shopId = 1;
        $this->configurationAdapter = new ConfigurationAdapter($shopId);

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

        $this->container = $kernel1->getContainer();
    }

    /**
     * @runInSeparateProcess
     */
    public function testShouldSetConfigurationValue()
    {
        $configurationService = $this->container->get('prestashop.adapter.legacy.configuration');
        $configurationService->set('VOLT_TEST', 'VOLT_TEST');

        $this->assertEquals('VOLT_TEST', $configurationService->get('VOLT_TEST'));
    }

    //    public function getService(string $service)
    //    {
    //        self::bootKernel();
    //        $container = self::$kernel->getContainer();
    //        $container = self::$container;
    //
    //        return self::$container->get($service);
    //    }

    /**
     * @runInSeparateProcess
     */
    public function testShouldGetIsString()
    {
        $string = $this->configurationAdapter->get('VOLT_TEST');
        $this->assertIsString($string);
    }

    public function testShouldGetIsFalse()
    {
        $this->configurationAdapter->update('VOLT_TEST', 0);
        $value = $this->configurationAdapter->get('VOLT_TEST');
        if ($value == 1) {
            $value = true;
        } else {
            $value = false;
        }
        $this->assertFalse($value);
    }

    public function testShouldUpdateValue()
    {
        $key = 'VOLT_TEST';
        $value = 'VOLT_TEST';
        $return = $this->configurationAdapter->update($key, $value);
        $this->assertTrue($return);
    }

    public function testShouldDeleteByName()
    {
        $key = 'VOLT_TEST';
        $return = $this->configurationAdapter->delete($key);
        $this->assertTrue($return);
    }
}
