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
class SuccessState implements IState
{
    /**
     * @var string
     */
    private $moduleName;

    private $configuration;

    /**
     * @var \OrderState
     */
    private $orderState;

    public $stateLanguage = [
        'pl' => 'Płatność zaakceptowana (Volt)',
        'en' => 'Payment accepted (Volt)',
        'fr' => 'Paiement accepté (Volt)',
        'de' => 'Zahlung akzeptiert (Volt)',
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
        $this->orderState->send_email = true;
        $this->orderState->invoice = true;
        $this->orderState->color = '#00DE69';
        $this->orderState->template = 'payment';
        $this->orderState->logable = true;
        $this->orderState->module_name = $this->moduleName;
        $this->orderState->paid = true;
        $this->orderState->pdf_invoice = true;
        $this->orderState->pdf_delivery = true;
        $this->orderState->add();

        \Configuration::updateValue('VOLT_SUCCESS_STATE_ID', $this->orderState->id);

        return $this->orderState;
    }
}
