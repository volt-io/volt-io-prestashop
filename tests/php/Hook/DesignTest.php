<?php

declare(strict_types=1);

namespace Volt\Tests\Hook;

use Mockery\Adapter\Phpunit\MockeryTestCase;
use Module;
use Volt;
use Volt\Tests\Mock\ConfigurationAdapterMock;

class DesignTest extends MockeryTestCase
{
    private $module;
    private $fakeConfiguration;
    private $design;

    protected function setUp(): void
    {
        $this->module = Module::getInstanceByName('volt');
        $this->fakeConfiguration = new ConfigurationAdapterMock(1);
        $this->design = new Volt\Hook\Design(
            $this->module,
            $this->fakeConfiguration
        );
    }

    public function testShouldGetThemeWidgetReturnArray()
    {
        $given = $this->design->getThemeWidget();
        $this->assertIsArray($given);
    }

    public function testShouldThemeConfigurationValue()
    {
        $shopId = 1;

        global $kernel;
        if ($kernel) {
            $kernel1 = $kernel;
        } else {
            require_once _PS_ROOT_DIR_ . '/app/AppKernel.php';
            $env = 'prod'; //_PS_MODE_DEV_ ? 'dev' : 'prod';
            $debug = false; //_PS_MODE_DEV_ ? true : false;
            $kernel1 = new \AppKernel($env, $debug);
            $kernel1->boot();
        }

        $this->container = $kernel1->getContainer();

        $configurationService = $this->container->get('prestashop.adapter.legacy.configuration');
        $configurationService->set('VOLT_COLOR', '#000000');

        $given = $this->design->themeConfigurationValue('#000000', 'VOLT_COLOR');

        $this->assertIsArray($given);

        $configurationService->delete('VOLT_COLOR', '#000000');
    }
}
