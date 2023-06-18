<?php
/**
 * NOTICE OF LICENSE.
 * This file is licenced under the Software License Agreement.
 * With the purchase or the installation of the software in your application
 * you accept the licence agreement.
 * You must not modify, adapt or create derivative works of this source code
 *
 * @author    Volt Technologies Holdings Limited
 * @copyright 2023, Volt Technologies Holdings Limited
 * @license   LICENSE.txt
 * @description Enable your customers to quickly and securely check out using their bank account.
 */
declare(strict_types=1);

if (!defined('_PS_VERSION_')) {
    exit;
}

if (file_exists(__DIR__ . '/vendor/autoload.php')) {
    require_once __DIR__ . '/vendor/autoload.php';
}

use Configuration as Cfg;
use PrestaShop\PrestaShop\Adapter\SymfonyContainer;
use PrestaShop\PrestaShop\Core\Payment\PaymentOption;
use Volt\Adapter\ConfigurationAdapter;
use Volt\Api\Client;
use Volt\Database\Configure;
use Volt\Database\Installer;
use Volt\Exception\DatabaseException;
use Volt\Factory\StateFactory;
use Volt\HookDispatcher;
use Volt\Service\Schedule;

class volt extends \PaymentModule
{
    public $_errors;
    public $_path;
    public $js_path;
    public $css_path;
    public $img_path;
    public $module_path;
    public $displayName;
    public $description;
    public $name;
    public $author;
    public $version;
    public $need_instance;
    public $confirmUninstall;
    public $tab;
    public $name_upper;
    public $bootstrap;
    public $currencies;
    public $currencies_mode;
    /**
     * @var array|string[]
     */
    private $hookDispatcher;
    public $api;

    public $tabs = [
          [
              'class_name' => 'AdminVoltGeneralController',
              'visible' => false,
              'name' => 'Volt',
              'parent_class_name' => 'ShopParameters',
          ],
          [
              'class_name' => 'AdminVoltAjaxController',
              'visible' => false,
              'name' => 'Volt',
              'parent_class_name' => 'ShopParameters',
          ],
    ];

    public function __construct()
    {
        $this->name = 'volt';
        $this->tab = 'payments_gateways';
        $this->name_upper = \Tools::strtoupper($this->name);
        $this->author = 'Volt Technologies Holdings Limited';
        $this->version = '1.0.5';
        $this->ps_versions_compliancy = ['min' => '1.7.5', 'max' => _PS_VERSION_];
        $this->currencies = true;
        $this->currencies_mode = 'checkbox';
        $this->need_instance = 0;
        $this->bootstrap = true;

        parent::__construct();

        if (!$this->_path) {
            $this->_path = __PS_BASE_URI__ . 'modules/' . $this->name . '/';
        }

        $this->js_path = $this->_path . 'views/js/';
        $this->css_path = $this->_path . 'views/css/';
        $this->img_path = $this->_path . 'views/img/';
        $this->module_path = $this->_path;
        $this->displayName = $this->l('Pay by Bank');
        $this->description = $this->l('Enable your customers to quickly and securely check out using their bank account.');
        $this->confirmUninstall = $this->l('Are you sure you want to uninstall this module?');
        $this->initAPI();

        $this->registerHook('actionAdminControllerSetMedia');
    }

    /**
     * Module call API.
     */
    private function initAPI()
    {
        $env = Cfg::get('VOLT_ENV');

        if ($env === '1') {
            $clientId = Cfg::get('VOLT_PROD_CLIENT_ID');
            $secretKey = Cfg::get('VOLT_PROD_CLIENT_SECRET');
            $username = Cfg::get('VOLT_PROD_USERNAME');
            $password = Cfg::get('VOLT_PROD_PASSWORD');
            $url = 'https://api.volt.io/';
        } else {
            $clientId = Cfg::get('VOLT_SANDBOX_CLIENT_ID');
            $secretKey = Cfg::get('VOLT_SANDBOX_CLIENT_SECRET');
            $username = Cfg::get('VOLT_SANDBOX_USERNAME');
            $password = Cfg::get('VOLT_SANDBOX_PASSWORD');
            $url = 'https://api.sandbox.volt.io/';
        }

        if ($clientId && $secretKey && $username && $password) {
            try {
                $this->api = new Client();
                $this->api->setEndpoint($url);
                $this->api->setClientSecret($secretKey);
                $this->api->setClientId($clientId);
                $this->api->setLogin($username);
                $this->api->setPassword($password);
                $this->api->getToken();
            } catch (\Exception $exception) {
                \PrestaShopLogger::addLog($exception->getMessage(), 3);
            }
        }

        $this->registerHook('actionDispatcherAfter');
        $this->registerHook('actionAdminControllerSetMedia');
    }

    /**
     * Install module
     *
     * @return bool
     * @throws Exception
     */
    public function install(): bool
    {
        if (version_compare(phpversion(), '7.2', '<')) {
            $this->_errors[] = $this->l(
                sprintf(
                    'Your PHP version is too old, please upgrade to a newer version. Your version is %s,' .
                    ' library requires %s',
                    phpversion(),
                    '7.2'
                )
            );
        }

        if (!parent::install() || false === (
            new Installer(
                $this
            )
            )->install()) {
            $this->_errors[] = $this->l('Installation error');
        }

        $this->registerHook('actionFrontControllerSetMedia');
        $this->registerHook('displayPayment');
        $this->registerHook('displayAdminOrderMainBottom');
        $this->registerHook('paymentOptions');
        $this->registerHook('hookPaymentReturn');

        if (false === (new StateFactory(
            $this,
            new \OrderState(),
            new ConfigurationAdapter($this->context->shop->id)
        ))->install()) {
            $this->_errors[] = $this->l('Installation state error');
            return false;
        }

        if (false === (new Configure(
            $this,
            new ConfigurationAdapter($this->context->shop->id),
            $this->getTranslator()
        ))->install()) {
            $this->_errors[] = $this->l('Installation configure');
            return false;
        }

        if (!empty($this->_errors)) {
            return false;
        }

        return true;
    }

    public function hookActionDispatcherAfter($params = null)
    {
        $schedule = new Schedule($this);
        $schedule->runSchedule();
    }

    /**
     * @param bool $keep
     *
     * @return bool
     * @throws DatabaseException|Exception
     */
    public function uninstall(bool $keep = true): bool
    {
        if (!parent::uninstall() || false === (new Installer($this) )->uninstall()) {
            $this->_errors[] = $this->l('Installation error');
        }

        if (!empty($this->_errors)) {
            return false;
        }

        return true;
    }

    /**
     * @param string $serviceName
     *
     * @return mixed
     */
    public function getService(string $serviceName)
    {
        $container = SymfonyContainer::getInstance();

        if ($container !== null) {
            return $container->get($serviceName);
        }

        return $this->get($serviceName);
    }

    /**
     * Redirect to main controller
     *
     * @return void
     */
    public function getContent()
    {
        Tools::redirectAdmin(
            $this->getContext()->link->getAdminLink('AdminVoltGeneral')
        );
    }

    /**
     * Main path
     *
     * @return string
     */
    public function getPathUri(): string
    {
        return $this->_path;
    }

    /**
     * Return current context
     *
     * @return Context
     */
    public function getContext(): Context
    {
        return $this->context;
    }

    protected function getHookDispatcher(): HookDispatcher
    {
        if (!isset($this->hookDispatcher)) {
            if (!class_exists(HookDispatcher::class)) {
                require_once __DIR__ . '/vendor/autoload.php';
            }

            $this->hookDispatcher = new HookDispatcher($this, new ConfigurationAdapter($this->context->shop->id));
        }

        return $this->hookDispatcher;
    }

    /**
     * Dispatch hooks
     *
     * @param string $methodName
     * @param array $arguments
     *
     * @return mixed
     */
    public function __call(string $methodName, array $arguments = [])
    {
        return $this
            ->getHookDispatcher()
            ->dispatch($methodName, $arguments[0] ?? []);
    }

    public function getAssetImages(): string
    {
        return $this->_path;
    }

    /**
     * Create payment methods
     */
    public function hookPaymentOptions($params)
    {
        if (!$this->active) {
            return null;
        }

        $option = new PaymentOption();
        $option
            ->setModuleName($this->name)
            ->setCallToActionText($this->l('Pay by Bank'))
            ->setLogo(Context::getContext()->shop->getBaseURL(true) . 'modules/volt/views/img/logo-payment.png')
            ->setAdditionalInformation(
                $this->fetch('module:volt/views/templates/hook/payment.tpl')
            );

        return [
            $option,
        ];
    }

    public function debug($context, $file): void
    {
        $filename = $file ?? 'test';
        $logDir = __DIR__ . '/log';

        $log = PHP_EOL . 'User: ' . $_SERVER['REMOTE_ADDR'] . ' - ' . date('F j, Y, g:i a') . PHP_EOL .
            print_r($context, true) . PHP_EOL .
            '-------------------------';
        file_put_contents($logDir . '/' . $filename . '_' . date('j.n.Y') . '.log', $log, FILE_APPEND);
    }

    public function hookPaymentReturn($params)
    {
        if (!$this->active) {
            return;
        }
        $state = $params['order']->getCurrentState();


        $this->smarty->assign('status', 'Accepted');


        return $this->display(__FILE__, 'payment_return.tpl');
    }

    public function translateStrings()
    {
        $this->module->l('The refund amount you entered is greater than order amount.');
        $this->module->l('Refund error:');
        $this->module->l('Couldn\'t create a refund for amount. Balance Exceeded');
        $this->module->l('Success refund');
    }
}
