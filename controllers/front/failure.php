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

if (!defined('_PS_VERSION_')) {
    exit;
}

class VoltFailureModuleFrontController extends ModuleFrontController
{
    public $ssl = true;
    public $display_column_left = false;

    /**
     * @throws PrestaShopDatabaseException
     * @throws PrestaShopException
     */
    public function initContent()
    {
        parent::initContent();

        $this->context->smarty->assign([
            'volt_dir' => $this->module->img_path,
            'home_url' => _PS_BASE_URL_,
            'urls' => $this->getTemplateVarUrls(),
        ]);

        $this->setTemplate('module:volt/views/templates/hook/payment_failure.tpl');
    }
}
