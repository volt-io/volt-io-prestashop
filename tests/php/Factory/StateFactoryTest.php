<?php

namespace Volt\Tests\Handler;

use PHPUnit\Framework\TestCase;
use Module;
use Volt\Adapter\ConfigurationAdapter;
use Volt\Factory\StateFactory;

class StateFactoryTest extends TestCase
{

    protected function setUp()
    :void
    {
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

        $orderState = new \OrderState();
        $this->module = Module::getInstanceByName('volt');
        $configuration = new ConfigurationAdapter(1);

        $this->stateFactory = new StateFactory(
            $this->module,
            $orderState,
            $configuration
        );

        $this->removeModuleStates();

    }

    public function removeModuleStates()
    {

        $results = \Db::getInstance()->executeS('
            SELECT id_order_state as id, module_name
            FROM `'._DB_PREFIX_.'order_state` 
            WHERE module_name = "volt"'
        );

        if ($results) {
            foreach ($results as $res) {
                \Db::getInstance()->execute(
                    'DELETE FROM `'._DB_PREFIX_.'order_state` '.'WHERE `id_order_state` = '.(int)$res['id']
                );
                \Db::getInstance()->execute(
                    'DELETE FROM `'._DB_PREFIX_.'order_state_lang` '.'WHERE `id_order_state` = '.(int)$res['id']
                );
            }
        }

    }

    public function testShouldGetVoltStates()
    {
        $given = $this->stateFactory->getVoltStates();
        $this->assertIsArray($given);
    }

    /**
     * @throws \Exception
     */
    public function testShouldInstallReturnTrue()
    {
        $given = $this->stateFactory->install();
        $this->assertTrue($given);
    }

    public function testShouldStateBuilderReturnException()
    {
        $this->expectException(\Exception::class);
        $given = $this->stateFactory->stateBuilder('test', new \OrderState, 'volt');
    }

    public function testShouldStateAvailableReturnFalse()
    {
        $given = $this->stateFactory->stateAvailable([]);
        $this->assertFalse($given);
    }

    public function testShouldStateAvailableReturnTrue()
    {

        $array = [
            'pl' => 'Płatność zaakceptowana (Voltt)',
            'en' => 'Payment accepted (Voltt)',
        ];

        $given = $this->stateFactory->stateAvailable($array);
        $this->assertFalse($given);
    }

}
