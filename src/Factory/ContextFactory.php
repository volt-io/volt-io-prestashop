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

use Context;

class ContextFactory
{
    /**
     * @return Context|null
     */
    public static function getContext()
    {
        return Context::getContext();
    }

    /**
     * @return \Cart
     */
    public static function getCart()
    {
        return Context::getContext()->cart;
    }

    /**
     * @return \Language|\PrestaShopBundle\Install\Language
     */
    public static function getLanguage()
    {
        return Context::getContext()->language;
    }

    /**
     * @return \Currency|null
     */
    public static function getCurrency()
    {
        return Context::getContext()->currency;
    }

    /**
     * @return \Smarty
     */
    public static function getSmarty()
    {
        return Context::getContext()->smarty;
    }

    /**
     * @return int
     */
    public static function getShop()
    {
        return Context::getContext()->shop->id;
    }

    /**
     * @return \AdminController|\FrontController
     */
    public static function getController()
    {
        return Context::getContext()->controller;
    }

    /**
     * @return \Cookie
     */
    public static function getCookie()
    {
        return Context::getContext()->cookie;
    }

    /**
     * @return \Link
     */
    public static function getLink()
    {
        return Context::getContext()->link;
    }
}
