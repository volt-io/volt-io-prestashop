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

namespace Volt\Adapter;

if (!defined('_PS_VERSION_')) {
    exit;
}

use Configuration as Cfg;

class ConfigurationAdapter
{
    private $shopId;

    public function __construct($shopId)
    {
        $this->shopId = $shopId;
    }

    public function get($key, $idLang = null, $idShopGroup = null, $idShop = null, $default = false)
    {
        if ($idShop === null) {
            $idShop = $this->shopId;
        }

        return Cfg::get($key, $idLang, $idShopGroup, $idShop, $default);
    }

    public function update($key, $values, $html = false, $idShopGroup = null, $idShop = null)
    {
        if ($idShop === null) {
            $idShop = $this->shopId;
        }

        return Cfg::updateValue($key, $values, $html, $idShopGroup, $idShop);
    }

    public function delete($key)
    {
        return Cfg::deleteByName($key);
    }
}
