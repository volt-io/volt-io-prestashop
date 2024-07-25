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

namespace Volt;

if (!defined('_PS_VERSION_')) {
    exit;
}

use Volt\Adapter\ConfigurationAdapter;
use Volt\Hook\Admin;
use Volt\Hook\Design;

class HookDispatcher
{
    public const HOOKS = [
        Design::class,
        Admin::class,
    ];

    /**
     * List of available hooks
     *
     * @var string[]
     */
    private $availableHooks = [];

    /**
     * Hook classes
     *
     * @var Hook\AbstractHook[]
     */
    protected $hooks = [];

    private $module;

    /**
     * Configuration
     *
     * @var ConfigurationAdapter
     */
    private $configuration;

    public function __construct(
        \Volt $module,
        ConfigurationAdapter $configuration
    ) {
        $this->module = $module;
        $this->configuration = $configuration;

        $this->createHook();
    }

    public function createHook(): void
    {
        foreach (static::HOOKS as $hookClass) {
            $hook = new $hookClass(
                $this->module,
                $this->configuration
            );

            $this->hooks[] = $hook;
        }
    }

    /**
     * Get available hooks
     *
     * @return string[]
     */
    public function getAvailableHooks(): array
    {
        return [
            'actionFrontControllerSetMedia',
            'displayPayment',
            'displayAdminOrderMainBottom',
            'paymentOptions',
            'hookPaymentReturn',
            'displayHeader',
        ];
    }

    /**
     * Find hook and dispatch it
     *
     * @param string $hookName
     * @param array $params
     *
     * @return mixed|void
     */
    public function dispatch(string $hookName, array $params = [])
    {
        foreach ($this->hooks as $hook) {
            if (method_exists($hook, $hookName)) {
                return $hook->{$hookName}($params);
            }
        }

        return false;
    }
}
