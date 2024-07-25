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

namespace Volt\Database;

if (!defined('_PS_VERSION_')) {
    exit;
}

use Symfony\Component\Translation\TranslatorInterface;
use Volt\Adapter\ConfigurationAdapter;

class Configure
{
    protected $name;
    /**
     * @var Volt
     */
    protected $module;
    /**
     * @var ConfigurationAdapter
     */
    protected $configuration;
    /**
     * @var TranslatorInterface
     */
    protected $translator;

    public function __construct(
        \Volt $module,
        ConfigurationAdapter $configuration,
        TranslatorInterface $translator
    ) {
        $this->module = $module;
        $this->configuration = $configuration;
        $this->translator = $translator;
        $this->name = \Tools::strtoupper($this->module->name);
    }

    public function install(): bool
    {
        if (!$this->installConfiguration(\Shop::isFeatureActive())) {
            $this->module->_errors[] = $this->module->l('Configuration install error');

            return false;
        }

        return true;
    }

    /**
     * Deleting configuration
     */
    public function uninstall()
    {
        $res = true;

        return $res;
    }

    /**
     * Create configuration fields
     *
     * @param $shopFeature
     *
     * @return bool
     */
    public function installConfiguration($shopFeature): bool
    {
        $res = true;
        if ($shopFeature) {
            foreach (\Shop::getContextListShopID() as $shop_id) {
                $group_id = \Shop::getGroupFromShop($shop_id);
                $res = $this->installMultistore($group_id, $shop_id);
            }
        } else {
            $res = $this->installShop();
        }

        return (bool) $res;
    }

    public function installMultistore($group_id, $shop_id): bool
    {
        $res = true;

        $res &= $this->configuration->update(
            $this->name . '_ENV',
            '2',
            false,
            $group_id,
            $shop_id
        );
        $res &= $this->configuration->update(
            $this->name . '_PROD_CLIENT_ID',
            '',
            false,
            $group_id,
            $shop_id
        );
        $res &= $this->configuration->update(
            $this->name . '_PROD_CLIENT_SECRET',
            '',
            false,
            $group_id,
            $shop_id
        );
        $res &= $this->configuration->update(
            $this->name . '_PROD_NOTIFICATION_SECRET',
            '',
            false,
            $group_id,
            $shop_id
        );
        $res &= $this->configuration->update(
            $this->name . '_PROD_USERNAME',
            '',
            false,
            $group_id,
            $shop_id
        );
        $res &= $this->configuration->update(
            $this->name . '_PROD_PASSWORD',
            '',
            false,
            $group_id,
            $shop_id
        );
        $res &= $this->configuration->update(
            $this->name . '_SANDBOX_CLIENT_ID',
            '',
            false,
            $group_id,
            $shop_id
        );
        $res &= $this->configuration->update(
            $this->name . '_SANDBOX_CLIENT_SECRET',
            '',
            false,
            $group_id,
            $shop_id
        );
        $res &= $this->configuration->update(
            $this->name . '_SANDBOX_NOTIFICATION_SECRET',
            '',
            false,
            $group_id,
            $shop_id
        );
        $res &= $this->configuration->update(
            $this->name . '_SANDBOX_USERNAME',
            '',
            false,
            $group_id,
            $shop_id
        );
        $res &= $this->configuration->update(
            $this->name . '_SANDBOX_PASSWORD',
            '',
            false,
            $group_id,
            $shop_id
        );
        $res &= $this->configuration->update(
            $this->name . '_CUSTOM_STATE',
            '',
            false,
            $group_id,
            $shop_id
        );
        $res &= $this->configuration->update(
            $this->name . '_PENDING_STATE_ID',
            '',
            false,
            $group_id,
            $shop_id
        );
        $res &= $this->configuration->update(
            $this->name . '_SUCCESS_STATE_ID',
            '',
            false,
            $group_id,
            $shop_id
        );
        $res &= $this->configuration->update(
            $this->name . '_FAILURE_STATE_ID',
            '',
            false,
            $group_id,
            $shop_id
        );

        return (bool) $res;
    }

    public function installShop(): bool
    {
        $res = true;
        /* Sets up Global configuration */
        $res &= $this->configuration->update(
            $this->name . '_ENV',
            '2'
        );
        $res &= $this->configuration->update(
            $this->name . '_PROD_CLIENT_ID',
            ''
        );
        $res &= $this->configuration->update(
            $this->name . '_PROD_CLIENT_SECRET',
            ''
        );
        $res &= $this->configuration->update(
            $this->name . '_PROD_NOTIFICATION_SECRET',
            ''
        );
        $res &= $this->configuration->update(
            $this->name . '_PROD_USERNAME',
            ''
        );
        $res &= $this->configuration->update(
            $this->name . '_PROD_PASSWORD',
            ''
        );
        $res &= $this->configuration->update(
            $this->name . '_SANDBOX_CLIENT_ID',
            ''
        );
        $res &= $this->configuration->update(
            $this->name . '_SANDBOX_CLIENT_SECRET',
            ''
        );
        $res &= $this->configuration->update(
            $this->name . '_SANDBOX_NOTIFICATION_SECRET',
            ''
        );
        $res &= $this->configuration->update(
            $this->name . '_SANDBOX_USERNAME',
            ''
        );
        $res &= $this->configuration->update(
            $this->name . '_SANDBOX_PASSWORD',
            ''
        );

        $res &= $this->configuration->update(
            $this->name . '_CUSTOM_STATE',
            ''
        );
        $res &= $this->configuration->update(
            $this->name . '_PENDING_STATE_ID',
            ''
        );
        $res &= $this->configuration->update(
            $this->name . '_SUCCESS_STATE_ID',
            ''
        );
        $res &= $this->configuration->update(
            $this->name . '_FAILURE_STATE_ID',
            ''
        );

        return (bool) $res;
    }
}
