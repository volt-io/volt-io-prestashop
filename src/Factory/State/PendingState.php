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
declare(strict_types=1);

namespace Volt\Factory\State;

if (!defined('_PS_VERSION_')) {
    exit;
}

class PendingState implements IState
{
    /**
     * @var string
     */
    private $moduleName;

    /**
     * @var \OrderState
     */
    private $orderState;

    private $configuration;

    public $stateLanguage = [
        'pl' => 'Oczekiwanie na płatność (Volt)',
        'en' => 'Waiting for payment (Volt)',
    ];

    public function __construct(
        \OrderState $orderState,
        $configuration,
        string $moduleName
    ) {
        $this->moduleName = $moduleName;
        $this->configuration = $configuration;
        $this->orderState = $orderState;
    }

    /**
     * @return \OrderState
     */
    public function create(): \OrderState
    {
        $name = [];

        foreach (\Language::getLanguages() as $lang) {
            $name[$lang['id_lang']] = $this->stateLanguage[$lang['iso_code']]
                ?? $this->stateLanguage['pl'];
        }

        $this->orderState->name = $name;
        $this->orderState->color = '#5bc0de';
        $this->orderState->send_email = false;
        $this->orderState->invoice = false;
        $this->orderState->logable = false;
        $this->orderState->module_name = $this->moduleName;
        $this->orderState->add();

        $stateId = $this->orderState->id;
        \Configuration::updateValue('VOLT_PENDING_STATE_ID', $stateId);

        return $this->orderState;
    }
}
