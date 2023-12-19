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

if (!defined('_PS_VERSION_')) {
    exit;
}
class AdminVoltAjaxController extends ModuleAdminController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function initContent(): void
    {
        if (!$this->loadObject(true)) {
            return;
        }
        $this->ajax = true;
    }

    public function ajaxProcessSaveConfiguration()
    {
        try {
            foreach (Helper::getFields() as $configField) {
                $value = Tools::getValue($configField, Cfg::get($configField));
                if ($value) {
                    Cfg::updateValue($configField, $value);
                }
            }
            $this->ajaxDie(json_encode(['success' => true]));
        } catch (Exception $exception) {
            PrestaShopLogger::addLog(
                'Volt - Ajax Error ' . $exception->getMessage(),
                4
            );
            $this->ajaxDie(json_encode(['success' => false]));
        }
    }
}
