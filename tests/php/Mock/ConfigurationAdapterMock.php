<?php

namespace Volt\Tests\Mock;

use Volt\Adapter\ConfigurationAdapter;

class ConfigurationAdapterMock extends ConfigurationAdapter
{
    /**
     * @var array
     */
    private $data = [];

    public function setFakeData(array $data)
    {
        $this->data = array_merge($this->data, $data);
    }

    public function get($key, $idLang = null, $idShopGroup = null, $idShop = null, $default = false)
    {
        return $this->data[$key] ?? false;
    }

    public function update($key, $values, $html = false, $idShopGroup = null, $idShop = null)
    :bool
    {
        // Simple registration, we don't take care about multi lang values etc.
        $this->data[$key] = $values;

        return true;

        //        return true;
    }

    public function delete($key)
    :bool
    {

        //        unset($this->data[$key]);

        $val = $this->data[$key] ?? false;
        unset($this->data[$key]);
        return (bool)$val;

        //        dump($key);

        //        return true;
    }

}
