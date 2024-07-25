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

if (!defined('_PS_VERSION_')) {
    exit;
}

use Volt\Adapter\ConfigurationAdapter;

abstract class AbstractHook
{
    protected $module;
    protected $context;

    /**
     * @var ConfigurationAdapter
     */
    protected $configuration;

    public function __construct(
        \Volt $module,
        ConfigurationAdapter $configuration
    ) {
        $this->module = $module;
        $this->configuration = $configuration;
        $this->context = $module->getContext();
    }
}
