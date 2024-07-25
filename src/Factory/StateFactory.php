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
 */

namespace Volt\Factory;

if (!defined('_PS_VERSION_')) {
    exit;
}

use Volt\Adapter\ConfigurationAdapter;
use Volt\Factory\State\FailureState;
use Volt\Factory\State\NotPaidState;
use Volt\Factory\State\PendingState;
use Volt\Factory\State\SuccessState;

class StateFactory
{
    private $module;
    private $orderState;
    private $configuration;

    /**
     * @var array
     */
    protected $voltStates = [];

    public function __construct(
        \Volt $module,
        \OrderState $orderState,
        ConfigurationAdapter $configuration
    ) {
        $this->module = $module;
        $this->orderState = $orderState;
        $this->configuration = $configuration;
        $this->voltStates = $this->getVoltStates();
    }

    public function getVoltStates(): array
    {
        return [
            'pending',
            'not_paid',
            'success',
            'failure',
        ];
    }

    /**
     * @throws \Exception
     */
    public function install(): bool
    {
        if (!empty($this->voltStates)) {
            foreach ($this->voltStates as $type) {
                $this->createVoltState($type);
            }
        }

        return true;
    }

    /**
     * @throws \Exception
     */
    public function createVoltState($type): void
    {
        $state = $this->stateBuilder($type, $this->orderState);
        if (!$this->stateAvailable($state->stateLanguage)) {
            $state->create();
        }
    }

    /**
     * @throws \Exception
     */
    public function stateBuilder(string $type, \OrderState $orderState, $moduleName = 'volt')
    {
        \Configuration::updateValue('VOLT_CUSTOM_STATE', '1');

        switch ($type) {
            case 'pending':
                $state = new PendingState($orderState, $this->configuration, $moduleName);
                break;
            case 'not_paid':
                $state = new NotPaidState($orderState, $this->configuration, $moduleName);
                break;
            case 'failure':
                $state = new FailureState($orderState, $this->configuration, $moduleName);
                break;
            case 'success':
                $state = new SuccessState($orderState, $this->configuration, $moduleName);
                break;
            default:
                throw new \Exception('Incorrect type when creating status for orders');
        }

        return $state;
    }


    private function existsLocalizedNameInDatabase(string $name, int $idLang, ?int $excludeIdOrderState): bool
    {
        return (bool) \Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue(
            'SELECT COUNT(*) AS count' .
            ' FROM ' . _DB_PREFIX_ . 'order_state_lang osl' .
            ' INNER JOIN ' . _DB_PREFIX_ . 'order_state os ON (os.`id_order_state` = osl.`id_order_state` 
            AND osl.`id_lang` = ' . $idLang . ')' .
            ' WHERE osl.id_lang = ' . $idLang .
            ' AND osl.name =  \'' . pSQL($name) . '\'' .
            ' AND os.deleted = 0' .
            ($excludeIdOrderState ? ' AND osl.id_order_state != ' . $excludeIdOrderState : '')
        );
    }


    /**
     * Checks if the status is available
     *
     * @param array $name
     *
     * @return bool
     */
    public function stateAvailable(array $name): bool
    {
        if (!empty($name)) {
            foreach (\Language::getLanguages() as $lang) {
                return $this->existsLocalizedNameInDatabase(
                    $name[$lang['iso_code']],
                    (int) $lang['id_lang'],
                    \Tools::getIsset('id_order_state') ? (int) \Tools::getValue('id_order_state') : null
                );
            }
        }

        return false;
    }
}
