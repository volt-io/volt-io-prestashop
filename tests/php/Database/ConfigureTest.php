<?php

declare(strict_types = 1);

namespace Volt\Tests\Database;

use Volt\Adapter\ConfigurationAdapter;
use Volt\Tests\Mock\ConfigurationAdapterMock;
use Context;
use Db;
use Mockery;
use PHPUnit\Framework\TestCase;
use Volt\Database\Configure;
use Module;
use Symfony\Component\Translation\TranslatorInterface;

class ConfigureTest extends TestCase
{
    private $module;
    private $configuration;

    private $fakeConfiguration;

    protected function setUp()
    :void
    {
        $this->module = Module::getInstanceByName('volt');
        $this->fakeConfiguration = new ConfigurationAdapterMock(1);

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

        $translate = $this->module->getTranslator();

        //        /** @var SymfonyContainer|AbstractQuery $queryMock */
        $queryMock = $this
            ->getMockBuilder(TranslatorInterface::class)
            ->disableOriginalConstructor()
            //            ->setMethods(['getResult'])
            ->getMockForAbstractClass();

        //        new Configure($module, $configuration, $translator)

        $this->configuration = new Configure(
            $this->module,
            $this->fakeConfiguration,
            $translate
        );

        //        $this->configuration = new Configure(
        //            $this->module,
        //            $this->fakeConfiguration,
        //            TranslatorInterface::class
        //        );

        //        if (false === (new Configure(
        //                $this,
        //                new ConfigurationAdapter($this->context->shop->id),
        //                $this->getTranslator()
        //            ))->execute()) {
        //            return false;
        //        }

        $data = [
            'VOLT_ENV' => '1',
            'VOLT_ENV_SWITCH' => '1',
            'VOLT_PROD_CLIENT_ID' => '1',
            'VOLT_PROD_CLIENT_SECRET' => '1',
            'VOLT_PROD_NOTIFICATION_SECRET' => '1',
            'VOLT_PROD_USERNAME' => '1',
            'VOLT_PROD_PASSWORD' => '1',

            'VOLT_SANDBOX_CLIENT_ID' => '1',
            'VOLT_SANDBOX_CLIENT_SECRET' => '1',
            'VOLT_SANDBOX_NOTIFICATION_SECRET' => '1',
            'VOLT_SANDBOX_USERNAME' => '1',
            'VOLT_SANDBOX_PASSWORD' => '1',

            'VOLT_CUSTOM_STATE' => '1',
            'VOLT_PENDING_STATE_ID' => '1',
            'VOLT_NOT_PAID_STATE_ID' => '1',
            'VOLT_SUCCESS_STATE_ID' => '1',
            'VOLT_FAILURE_STATE_ID' => '1',

            'VOLT_VISUAL_SETTINGS' => '1'
        ];

        $this->fakeConfiguration->setFakeData(
            $data
        );
    }

    public function testShouldCorrectInstallConfigure()
    :void
    {
        $this->assertEquals(true, $this->configuration->install());
    }

    public function testShouldCorrectUninstallConfigure()
    :void
    {
        $this->assertEquals(true, $this->configuration->uninstall());
    }

    public function testShouldStoreInstallConfigurationReturnFalse()
    :void
    {

        $stub = $this->getMockBuilder(Configure::class)
            ->setConstructorArgs([
                $this->module,
                $this->fakeConfiguration,
                $this->module->getTranslator()
            ])
            ->onlyMethods(['installConfiguration'])
            ->getMock();

        $stub->expects($this->any())->method('installConfiguration')->willReturn(false);

        $this->assertSame(false, $stub->install());

    }

    public function testShouldInstallMultistoreReturnTrue()
    {
        $given = $this->configuration->installMultistore(0, 0);

        $this->assertTrue($given);
    }

    public function testShouldInstallShopReturnTrue()
    {
        $given = $this->configuration->installShop();

        $this->assertTrue($given);
    }

    public function testShouldMultiStoreInstallConfigurationReturnTrue()
    :void
    {
        $this->assertEquals(true, $this->configuration->installConfiguration(\Shop::isFeatureActive()));
    }

    public function testShouldStoreInstallConfigurationReturnTrue()
    :void
    {
        $this->assertEquals(true, $this->configuration->installConfiguration(0));
    }

    public function tearDown()
    :void
    {
        unset($this->module);
    }
}
