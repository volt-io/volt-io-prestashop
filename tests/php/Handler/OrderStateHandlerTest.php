<?php

namespace Volt\Tests\Handler;

use PHPUnit\Framework\TestCase;
use Module;
use Volt\Adapter\ConfigurationAdapter;
use Volt\Handler\OrderStateHandler;

class OrderStateHandlerTest extends TestCase
{

    protected function setUp(): void
    {
        global $kernel;
        if($kernel){
            $kernel1 = $kernel;
        }
        // otherwise create it manually
        else {
            require_once _PS_ROOT_DIR_.'/app/AppKernel.php';
            $env = 'prod';//_PS_MODE_DEV_ ? 'dev' : 'prod';
            $debug = false;//_PS_MODE_DEV_ ? true : false;
            $kernel1 = new \AppKernel($env, $debug);
            $kernel1->boot();
        }


        $orderHistory = new \OrderHistory();
        $configuration = new ConfigurationAdapter(1);
        $transactionRepository = $kernel1->getContainer()->get("volt.repository.transaction");

        $this->orderStateHandler = new OrderStateHandler(
            $orderHistory,
            $configuration,
            $transactionRepository
        );

    }

    public function testShouldGetOrderStatusHistoryReturnArray() {

        $array = [
            'test' => [
                'test' => 2
            ]
        ];

        $given = $this->orderStateHandler->getOrderStatusesByHistory($array);
        $this->assertIsArray($given);
    }
}
