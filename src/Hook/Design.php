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

namespace Volt\Hook;

class Design extends AbstractHook
{
    public const HOOK_LIST = [
        'actionFrontControllerSetMedia',
        'paymentOptions',
        'displayPayment',
        'paymentReturn',
        'displayHeader',
        'actionAdminControllerSetMedia',
    ];

    /**
     * Hook header assets scripts and styles
     * @codeCoverageIgnore
     *
     * @return void
     * @throws \Exception
     */
    public function hookActionFrontControllerSetMedia(): void
    {
        $this->context->controller->registerStylesheet(
            'volt',
            'modules/' . $this->module->name . '/views/css/frontend.css'
        );

        $this->context->controller->registerJavascript(
            'volt-scripts',
            'modules/' . $this->module->name . '/views/js/frontend.min.js',
            [
                'position' => 'bottom',
                'priority' => 151,
            ]
        );

        $this->context->controller->registerJavascript(
            'volt-cdn',
            'https://js.volt.io/v1',
            [
                'position' => 'bottom',
                'priority' => 10,
                'inline' => true,
                'server' => 'remote',
            ]
        );

        $this->context->controller->registerJavascript(
            'volt',
            'https://js.volt.io/v1',
            [
                'position' => 'bottom',
                'priority' => 151,
            ]
        );

        $ajax = $this->context->link->getModuleLink('volt', 'initPayment', [], true);
        \Media::addJsDef(
            [
                'voltSettings' => [
                    'ajax_url' => $ajax,
                    'env' => $this->configuration->get('VOLT_ENV'),
                    'country' => $this->context->language->iso_code,
                    'language' => $this->context->country->iso_code,
                    'errorMsg' => $this->module->l('Widget initialization error, please validate data in payment module'),
                ],
            ]
        );
    }

    public function hookDisplayHeader()
    {
        $this->context->smarty->assign([
            'volt_dir' => $this->module->img_path,
        ]);
        return $this->context->smarty->fetch('module:volt/views/templates/front/modal.tpl');
    }

    public function hookActionAdminControllerSetMedia(array $params)
    {
        if (get_class($this->context->controller) == 'AdminVoltGeneralController') {
            $this->context->controller->addCss($this->module->getPathUri() . 'views/css/backend.css');
            $this->context->controller->addJS($this->module->getPathUri() . 'views/js/backend.min.js');
        }
    }
}
