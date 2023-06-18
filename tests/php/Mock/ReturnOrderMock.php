<?php

namespace Volt\Tests\Mock;

use phpDocumentor\Reflection\Types\Object_;

class ReturnOrderMock
{
    /**
     * @var array
     */
    private $data = [];

    public $id;
    public $total_paid;
    public $total_shipping;
    public $carrier_tax_rate;
    public $id_currency;
    public $module;

    public function __construct(
        $id,
        $total_paid,
        $total_shipping,
        $carrier_tax_rate,
        $id_currency,
        $module
    ) {
        $this->id = $id;
        $this->total_paid = $total_paid;
        $this->total_shipping = $total_shipping;
        $this->carrier_tax_rate = $carrier_tax_rate;
        $this->id_currency = $id_currency;
        $this->module = $module;
    }

    public function setProducts(array $data)
    {
        $this->data = array_merge($this->data, $data);
    }

    public function getProducts(): array
    {
        return $this->data;
    }

}
