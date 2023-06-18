<?php

namespace Volt\Tests\Mock;

class OrderDataMock
{
    public function getData()
    :array
    {
        return [
            'currencyCode' => 'GBP',
            'amount' => 1111,
            'type' => 'OTHER',
            'uniqueReference' => 'te'.time(),
            'payer' => [
                'reference' => 'te'.time(),
                'email' => 'test@test.pl',
                'name' => 'test test',
            ],
            "callback" => "crc=123123123",
        ];
    }
}
