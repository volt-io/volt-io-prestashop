<?php

declare(strict_types = 1);

namespace Volt\Tests\Api;

use Context;
use Db;
use PHPUnit\Framework\TestCase;
use Module;
use Volt\Api\Client;
use Volt\Exception\ApiException;
use Volt\Tests\Mock\OrderDataMock;

class ClientTest extends TestCase
{
    private $module;

    protected function setUp()
    :void
    {
        $this->module = Module::getInstanceByName('volt');
        $this->client = new Client();
    }

    public function testInstance()
    {
        $instance = $this->client::getInstance();
        $this->assertEquals($this->client, $instance);
    }

    public function testShouldSetEndpoint()
    {
        $expected = 'https://api.sandbox.volt.io/';
        $this->client->setEndpoint('https://api.sandbox.volt.io/');

        $this->assertEquals($expected, $this->client->endpoint);
        $this->assertNotEmpty($this->client->endpoint);
    }

    public function testShouldSetClientId()
    {
        $expected = SANDBOX_CLIENT_ID;
        $this->client->setClientId(SANDBOX_CLIENT_ID);

        $this->assertEquals($expected, $this->client->api_client_id);
        $this->assertNotEmpty($this->client->api_client_id);
    }

    public function testShouldSetClientSecret()
    {
        $expected = SANDBOX_CLIENT_SECRET;
        $this->client->setClientSecret(SANDBOX_CLIENT_SECRET);

        $this->assertEquals($expected, $this->client->api_client_secret);
        $this->assertNotEmpty($this->client->api_client_secret);
    }

    public function testShouldSetUsername()
    {
        $expected = SANDBOX_LOGIN;
        $this->client->setLogin(SANDBOX_LOGIN);

        $this->assertEquals($expected, $this->client->api_login);
        $this->assertNotEmpty($this->client->api_login);
    }

    public function testShouldSetPassword()
    {
        $expected = SANDBOX_PASSWORD;
        $this->client->setPassword(SANDBOX_PASSWORD);

        $this->assertEquals($expected, $this->client->api_password);
        $this->assertNotEmpty($this->client->api_password);
    }

    public function setCorrectSandbox()
    {
        $this->client->setEndpoint('https://api.sandbox.volt.io/');
        $this->client->setClientId(SANDBOX_CLIENT_ID);
        $this->client->setClientSecret(SANDBOX_CLIENT_SECRET);
        $this->client->setLogin(SANDBOX_LOGIN);
        $this->client->setPassword(SANDBOX_PASSWORD);
    }

    public function setFailSandbox()
    {
        $this->client->setEndpoint('https://example.com/');
        $this->client->setClientId('test');
        $this->client->setClientSecret('test');
        $this->client->setLogin(SANDBOX_LOGIN);
        $this->client->setPassword(SANDBOX_PASSWORD);
    }

    public function testShouldGetTokenReturnException()
    {
        $this->setFailSandbox();
        $token = $this->client->getToken(false);

        $this->assertNull($token);
    }

    public function testShouldGetTokenReturnCorrectToken()
    {
        $this->setCorrectSandbox();

        $expectedLength = 1168;
        $token = $this->client->getToken();

        $this->assertEquals($expectedLength, strlen($token));
    }

    /**
     * @throws ApiException
     */
    public function testShouldPostRequestNoClientId()
    {
        $this->client->setEndpoint('https://api.sandbox.volt.io/');

        $this->expectException(\Exception::class);
        $exception = $this->client->request('POST', 'test');
    }

    /**
     * @throws ApiException
     */
    public function testShouldPostRequestNoClientSecret()
    {
        $this->client->setEndpoint('https://api.sandbox.volt.io/');
        $this->client->setClientId(SANDBOX_CLIENT_ID);

        $this->expectException(\Exception::class);
        $exception = $this->client->request('POST', 'test');
    }

    public function testShouldPostRequestNoLogin()
    {
        $this->client->setEndpoint('https://api.sandbox.volt.io/');
        $this->client->setClientId(SANDBOX_CLIENT_ID);
        $this->client->setClientSecret(SANDBOX_CLIENT_SECRET);

        $this->expectException(\Exception::class);
        $exception = $this->client->request('POST', 'test');
    }

    public function testShouldPostRequestNoPassword()
    {
        $this->client->setEndpoint('https://api.sandbox.volt.io/');
        $this->client->setClientId(SANDBOX_CLIENT_ID);
        $this->client->setClientSecret(SANDBOX_CLIENT_SECRET);
        $this->client->setLogin(SANDBOX_LOGIN);

        $this->expectException(\Exception::class);
        $exception = $this->client->request('POST', 'test');
    }

    /**
     * @throws \Exception
     */
    public function testShouldPostRequestCurl()
    {
        $this->client->setEndpoint('https://api.sandbox.volt.io/');
        $this->client->setClientId(SANDBOX_CLIENT_ID);
        $this->client->setClientSecret(SANDBOX_CLIENT_SECRET);
        $this->client->setLogin(SANDBOX_LOGIN);
        $this->client->setPassword(SANDBOX_PASSWORD);

        $orderDataMock = new OrderDataMock();
        $data = $orderDataMock->getData();

        $response = $this->client->request('POST', 'dropin-payments', $data, true);
        $this->assertIsObject($response);
    }

    public function testShouldPostRequestFailCurl()
    {
        $this->setFailSandbox();
        //
        $orderDataMock = new OrderDataMock();
        $data = $orderDataMock->getData();

        $this->expectException(ApiException::class);
        //        $exception = $this->client->request('POST', 'test');

        $response = $this->client->request('POST', 'dropin-payments', $data, true, true);
        $this->assertNull($response);
    }

}
