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

use Volt\Exception\DatabaseException;

if (!defined('_PS_VERSION_')) {
    exit;
}

class Installer
{
    protected $module;

    public function __construct(\Volt $module)
    {
        $this->module = $module;
    }

    /**
     * @throws DatabaseException
     */
    public function install(): bool
    {
        $url = $this->module->getLocalPath() . 'src/Database/sql/install.sql';
        if (!$this->installDb($url)) {
            $this->module->_errors[] = $this->module->l('Table installation error');

            return false;
        }

        if (!$this->installContext()) {
            $this->module->_errors[] = $this->module->l('Context installation error');

            return false;
        }

        return true;
    }

    /**
     * @throws \Exception
     */
    public function uninstall(): bool
    {
        return true;
    }

    /**
     * Sql data installation
     *
     * @param $sql_path
     *
     * @return bool
     *
     * @throws DatabaseException
     */
    public function installDb($sql_path): bool
    {
        if (!file_exists($sql_path)) {
            return false;
        }

        return $this->executeSqlFromFile($sql_path, \Db::getInstance());
    }

    public function uninstallDb($sql_path): bool
    {
        if (!file_exists($sql_path)) {
            return false;
        }

        return $this->executeSqlFromFile($sql_path, \Db::getInstance());
    }

    public function installContext(): bool
    {
        if (\Shop::isFeatureActive()) {
            \Shop::setContext(\Shop::CONTEXT_ALL);
        }

        return true;
    }

    /**
     * Execute sql files
     *
     * @param string $path
     * @param $db
     *
     * @return bool
     *
     * @throws DatabaseException
     */
    public function executeSqlFromFile(string $path, $db): bool
    {
        $install = true;
        $sql = \Tools::file_get_contents($path);
        $sql = str_replace(['_DB_PREFIX_', '_MYSQL_ENGINE_'], [_DB_PREFIX_, _MYSQL_ENGINE_], $sql);

        $sqlQuery = explode(';', $sql);
        foreach ($sqlQuery as $q) {
            $q = trim($q);
            if (!empty($q)) {
                $install = $db->execute($q);
                if (!$install) {
                    throw new DatabaseException();
                }
            }
        }

        return $install;
    }
}
