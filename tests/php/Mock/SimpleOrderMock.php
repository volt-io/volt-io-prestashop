<?php

namespace Volt\Tests\Mock;

class SimpleOrderMock
{
    public $id = 0;

    public function addOrderPayment($arg1, $arg2)
    {
        return false;
    }

    public function getOrdersTotalPaid()
    {
        return false;
    }
}
