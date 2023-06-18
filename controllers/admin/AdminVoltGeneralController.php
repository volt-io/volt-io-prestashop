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

use Configuration as Cfg;
use Volt\Util\Helper;

class AdminVoltGeneralController extends ModuleAdminController
{
    /**
     * @var true
     */
    public $bootstrap;
    public $meta_title;

    public function __construct()
    {
        parent::__construct();

        $this->bootstrap = true;
        $this->meta_title = $this->module->l('VOLT - Configuration');

        if (!$this->module->active) {
            Tools::redirectAdmin($this->context->link->getAdminLink('AdminModules', true));
        }
    }

    public function initToolBarTitle()
    {
        $this->toolbar_title[] = $this->module->l('VOLT - Configuration');
    }

    public function initPageHeaderToolbar()
    {
    }

    public function initToolbarFlags()
    {
        $this->getLanguages();
        $this->context->smarty->assign([
            'maintenance_mode' => !(bool) Configuration::get('PS_SHOP_ENABLE'),
            'debug_mode' => (bool) _PS_MODE_DEV_,
            'lite_display' => $this->lite_display,
            'url_post' => self::$currentIndex . '&token=' . $this->token,
            'show_page_header_toolbar' => $this->show_page_header_toolbar,
            'page_header_toolbar_title' => $this->page_header_toolbar_title,
            'title' => $this->page_header_toolbar_title,
            'toolbar_btn' => $this->page_header_toolbar_btn,
            'page_header_toolbar_btn' => $this->page_header_toolbar_btn,
        ]);
    }

    public function renderView()
    {
        return $this->renderForm();
    }

    public function initContent()
    {
        if (!$this->loadObject(true)) {
            return;
        }

        parent::initContent();

        $this->content .= $this->renderForm();

        $this->context->smarty->assign([
            'content' => $this->content,
        ]);

        $currentPage = 'global';
        $getPage = Tools::getValue('page');
        if (!empty($getPage)) {
            $currentPage = $getPage;
        }

        $this->context->smarty->assign([
            'ps_version' => _PS_VERSION_,
            'timer_start' => $this->timer_start,
            'iso_is_fr' => strtoupper($this->context->language->iso_code) == 'FR',
            'languages' => Language::getLanguages(),
            'currentPage' => $currentPage,
            'defaultFormLanguage' => (int) $this->context->employee->id_lang,
        ]);
    }

    public function renderForm()
    {
        $fields_form = [];
        $id_default_lang = (int) Configuration::get('PS_LANG_DEFAULT');
        $statuses = \OrderState::getOrderStates($id_default_lang, true);
        $fields_form[0]['form'] = [
            'section' => [
                'title' => $this->l('Authentication'),
            ],
            'legend' => [
                'title' => $this->l('Authentication'),
            ],
            'input' => [
                [
                    'type' => 'radio',
                    'label' => $this->l('Environment'),
                    'name' => $this->module->name_upper . '_ENV',
                    'values' => [
                        [
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Set Production environment'),
                        ],
                        [
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->l('Set Sandbox environment'),
                        ],
                    ],
                    'help' => $this->l(
                        'It allows you to verify the operation of the module without the need to actually pay
                         for the order (in the test mode, no fees are charged for the order).'
                    ),
                ],
                [
                    'type' => 'switch-choose',
                    'label' => '',
                    'name' => $this->module->name_upper . '_ENV_SWITCH',
                    'size' => 'auto',
                    'values' => [
                        [
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Configure Production credentials'),
                        ],
                        [
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->l('Configure Sandbox credentials'),
                        ],
                    ],
                    'help' => $this->l(
                        'It allows you to verify the operation of the module without the need to actually pay
                         for the order (in the test mode, no fees are charged for the order).'
                    ),
                ],
                [
                    'type' => 'infoheading',
                    'label' => $this->l('Client credentials'),
                    'name' => $this->module->name_upper . '_SANDBOX_CLIENT_CREDENTIALS',
                ],
                // Sandbox
                [
                    'type' => 'text',
                    'label' => $this->l('Client ID'),
                    'name' => $this->module->name_upper . '_SANDBOX_CLIENT_ID',
                ],
                [
                    'type' => 'text',
                    'label' => $this->l('Client secret'),
                    'name' => $this->module->name_upper . '_SANDBOX_CLIENT_SECRET',
                ],
                [
                    'type' => 'text',
                    'label' => $this->l('Notifications secret'),
                    'name' => $this->module->name_upper . '_SANDBOX_NOTIFICATION_SECRET',
                ],
                [
                    'type' => 'infoheading',
                    'label' => $this->l('Customer credentials'),
                    'name' => $this->module->name_upper . '_SANDBOX_CUSTOMER_CREDENTIALS',
                ],
                [
                    'type' => 'text',
                    'label' => $this->l('Username'),
                    'name' => $this->module->name_upper . '_SANDBOX_USERNAME',
                ],
                [
                    'type' => 'text',
                    'label' => $this->l('Password'),
                    'name' => $this->module->name_upper . '_SANDBOX_PASSWORD',
                ],
                [
                    'type' => 'infoheading',
                    'label' => $this->l('Client credentials'),
                    'name' => $this->module->name_upper . '_PROD_CLIENT_CREDENTIALS',
                ],
                [
                    'type' => 'text',
                    'label' => $this->l('Client ID'),
                    'name' => $this->module->name_upper . '_PROD_CLIENT_ID',
                ],
                [
                    'type' => 'text',
                    'label' => $this->l('Client secret'),
                    'name' => $this->module->name_upper . '_PROD_CLIENT_SECRET',
                ],
                [
                    'type' => 'text',
                    'label' => $this->l('Notifications secret'),
                    'name' => $this->module->name_upper . '_PROD_NOTIFICATION_SECRET',
                ],
                [
                    'type' => 'infoheading',
                    'label' => $this->l('Customer credentials'),
                    'name' => $this->module->name_upper . '_PROD_CUSTOMER_CREDENTIALS',
                ],
                // Customer credentials
                [
                    'type' => 'text',
                    'label' => $this->l('Username'),
                    'name' => $this->module->name_upper . '_PROD_USERNAME',
                ],
                [
                    'type' => 'text',
                    'label' => $this->l('Password'),
                    'name' => $this->module->name_upper . '_PROD_PASSWORD',
                ],
            ],
            'submit' => [
                'title' => $this->l('Save'),
                'class' => 'btn btn-primary pull-right',
            ],
        ];

        $fields_form[1]['form'] = [
            'section' => [
                'title' => $this->l('Payment settings'),
            ],
            'legend' => [
                'title' => $this->l('Statuses'),
            ],
            'input' => [
                [
                    'type' => 'switch',
                    'label' => $this->l('Volt statuses'),
                    'name' => $this->module->name_upper . '_CUSTOM_STATE',
                    'values' => [
                        [
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->l('YES'),
                        ],
                        [
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->l('NO'),
                        ],
                    ],
                    'help' => $this->l(
                        ''
                    ),
                ],
                [
                    'type' => 'select',
                    'name' => $this->module->name_upper . '_PENDING_STATE_ID',
                    'label' => $this->l('Payment started'),
                    'options' => [
                        'query' => $statuses,
                        'id' => 'id_order_state',
                        'name' => 'name',
                    ],
                ],
                [
                    'type' => 'select',
                    'name' => $this->module->name_upper . '_NOT_PAID_STATE_ID',
                    'label' => $this->l('Not paid'),
                    'options' => [
                        'query' => $statuses,
                        'id' => 'id_order_state',
                        'name' => 'name',
                    ],
                ],
                [
                    'type' => 'select',
                    'name' => $this->module->name_upper . '_SUCCESS_STATE_ID',
                    'label' => $this->l('Payment approved'),
                    'options' => [
                        'query' => $statuses,
                        'id' => 'id_order_state',
                        'name' => 'name',
                    ],
                ],
                [
                    'type' => 'select',
                    'name' => $this->module->name_upper . '_FAILURE_STATE_ID',
                    'label' => $this->l('Payment failed'),
                    'options' => [
                        'query' => $statuses,
                        'id' => 'id_order_state',
                        'name' => 'name',
                    ],
                ],
            ],
            'submit' => [
                'title' => $this->l('Save'),
                'class' => 'btn btn-primary pull-right',
            ],
        ];

        $helper = new HelperForm();
        $helper->module = $this->module;
        $helper->name_controller = $this->module->name;
        $helper->token = Tools::getAdminTokenLite('AdminVoltGeneral');
        $helper->currentIndex = AdminController::$currentIndex;
        $helper->default_form_language = $id_default_lang;
        $helper->allow_employee_form_lang = $id_default_lang;
        $helper->title = $this->module->displayName;
        $helper->show_toolbar = true;
        $helper->toolbar_scroll = true;
        $helper->submit_action = 'submit' . $this->module->name;

        $link = new Link();
        $ajax_controller = $link->getAdminLink('AdminVoltAjax');

        $helper->tpl_vars = [
            'fields_value' => $this->getConfigFieldsValues(),
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id,
            'ajax_token' => Tools::getAdminTokenLite('AdminVoltAjax'),
            'ajax_controller' => $ajax_controller,
        ];

        return $helper->generateForm($fields_form);
    }

    /**
     * Get form values
     *
     * @return array
     */
    private function getConfigFieldsValues(): array
    {
        $data = [];
        foreach (Helper::getFields() as $field) {
            $data[$field] = Tools::getValue($field, Cfg::get($field));
        }

        return $data;
    }
}
