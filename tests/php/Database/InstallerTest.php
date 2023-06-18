<?php

declare(strict_types=1);

namespace Volt\Tests\Database;

use Db;
use Exception;
use Module;
use Symfony\Component\Translation\Translator;
use Tools;
use Tab;
use PrestaShopLogger;
use Language;
use Shop;
use Context;
use Mockery;
use PHPUnit\Framework\TestCase;
use Mockery\Adapter\Phpunit\MockeryTestCase;
use Currency;
use Volt\Exception\DatabaseException;
use Volt\Database\Installer;
class InstallerTest extends \PHPUnit\Framework\TestCase
{
    private $module;
    private $installer;
    private $translator;
    private $container;
    private $context;
    private $db;

//    public static URL =  'src/Database/sql/install.sql';

    protected function setUp(): void
    {
        $this->module = Module::getInstanceByName('volt');
        $this->translator = new Translator('pl_PL');
        $this->installer = new Installer($this->module);
        $this->db = Db::getInstance();
//        global $kernel;
//        if($kernel){
//            $kernel1 = $kernel;
//        }
//        // otherwise create it manually
//        else {
//            require_once _PS_ROOT_DIR_.'/app/AppKernel.php';
//            $env = 'prod';//_PS_MODE_DEV_ ? 'dev' : 'prod';
//            $debug = false;//_PS_MODE_DEV_ ? true : false;
//            $kernel1 = new \AppKernel($env, $debug);
//            $kernel1->boot();
//        }
//
//        $this->container = $kernel1->getContainer();

        $this->sql = $this->module->getLocalPath() . 'src/Database/sql/install.sql';
        $this->fakeSql = $this->module->getLocalPath() . 'src/Database/sql/fakeInstall.sql';

    }

    public function testShouldInstallSuccess()
    {
//        $installerService = $this->container->get("");
        $this->assertTrue($this->installer->install());
    }

    /**
     * @throws DatabaseException
     */
    public function testShouldInstallDbSuccess()
    {
        $this->assertTrue($this->installer->installDb($this->sql));
    }

    /**
     * @throws DatabaseException
     */
    public function testShouldInstallDbReturnFalse()
    :void
    {
        $stub = $this->getMockBuilder(Installer::class)
            ->setConstructorArgs([
                $this->module
            ])
            ->onlyMethods(['installDb'])
            ->getMock();

        $stub->expects($this->any())->method('installDb')->willReturn(false);

        $this->assertSame(false, $stub->install());
    }

    /**
     * @throws DatabaseException
     */
    public function testShouldInstallContextReturnFalse()
    :void
    {
        $stub = $this->getMockBuilder(Installer::class)
            ->setConstructorArgs([
                $this->module
            ])
            ->onlyMethods(['installContext'])
            ->getMock();

        $stub->expects($this->any())->method('installContext')->willReturn(false);

        $this->assertSame(false, $stub->install());
    }


    /**
     * @throws DatabaseException
     */
    public function testShouldInstallDbFail()
    {
        $this->assertFalse($this->installer->installDb($this->fakeSql));
    }

    /**
     * @throws Exception
     */
    public function testShouldUninstallReturnTrue()
    {
        $given = $this->installer->uninstall();
        $this->assertIsBool($given);
        $this->assertTrue($given);
    }


    public function testShouldUninstallDbReturnFalse()
    {
        $this->assertFalse($this->installer->uninstallDb($this->fakeSql));
    }

    public function testShouldUninstallDb()
    {
        $this->assertTrue($this->installer->uninstallDb($this->sql));
    }

    /**
     * @throws DatabaseException
     */
    public function testShouldExecuteSqlFromFileSuccess()
    {
        $this->assertTrue($this->installer->executeSqlFromFile($this->sql, $this->db));
    }

    public function testShouldExecuteSqlFromFileReturnException()
    {
        try {
            $this->installer->executeSqlFromFile('', $this->db);
            $this->fail('An exception should have been thrown.');
        } catch (\Exception $e) {
            $this->assertEquals(0, $e->getCode());
        }
    }

    public function testShouldInstallContext()
    {
        $this->assertTrue($this->installer->installContext());
    }
}
