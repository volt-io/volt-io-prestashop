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

namespace Volt\Api;

if (!defined('_PS_VERSION_')) {
    exit;
}

use Volt\Exception\ApiException;

class Client
{
    private static $instance;
    protected $ch;
    public $api_client_id;
    public $api_client_secret;
    public $api_login;
    public $api_password;

    public $endpoint;

    public static function getInstance(): Client
    {
        if (!self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * Set the API Key and trim whitespaces
     *
     * @param $api_client_id
     */
    public function setClientId($api_client_id)
    {
        $this->api_client_id = trim($api_client_id);
    }

    /**
     * Set the API Secret code and trim whitespaces
     *
     * @param $api_client_secret
     */
    public function setClientSecret($api_client_secret)
    {
        $this->api_client_secret = trim($api_client_secret);
    }

    public function setLogin($api_login)
    {
        $this->api_login = trim($api_login);
    }

    public function setPassword($api_password)
    {
        $this->api_password = trim($api_password);
    }

    public function setEndpoint($endpoint_url)
    {
        $this->endpoint = trim($endpoint_url);
    }

    /**
     * Request function to call our API Rest Payment Server
     *
     * @param $method
     * @param $api_method
     * @param null $body
     * @param bool $analyst
     *
     * @return mixed
     *
     * @throws ApiException
     */
    public function request($method, $api_method, $body = null, bool $analyst = false)
    {
        if (empty($this->api_client_id)) {
            throw new ApiException('Please configure your Client ID.');
        }

        if (empty($this->api_client_secret)) {
            throw new ApiException('Please configure API secret');
        }

        if (empty($this->api_login)) {
            throw new ApiException('Please configure API username');
        }

        if (empty($this->api_password)) {
            throw new ApiException('Please configure API password');
        }
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 1);

        curl_setopt($ch, CURLOPT_URL, $this->endpoint . $api_method);
        curl_setopt($ch, CURLINFO_HEADER_OUT, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);

        $authorization = $this->getToken();

        $api_headers = [
            'Authorization: Bearer ' . $authorization,
            'Content-Type: application/json',
        ];

        if ($analyst) {
            $api_headers[] = 'Volt-Partner-Attribution-Id: 78884f87-0171-4937-9d3c-99f36400c4c5';
        }

        curl_setopt($ch, CURLOPT_HTTPHEADER, $api_headers);

        if ($body && is_array($body)) {
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body));
        }

        // SSL
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            $exception_no = curl_errno($ch);
            $exception = curl_error($ch);
            throw new ApiException('Error (' . $exception_no . '):' . $exception);
        }

        $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $response_body = substr($response, $header_size);

        curl_close($ch);
        $ch = null;

        return json_decode($response_body);
    }

    public function getToken($customEndpoint = false, $method = 'oauth')
    {
        $api_method = $method;

        $data = [
            'client_id' => $this->api_client_id,
            'client_secret' => $this->api_client_secret,
            'username' => $this->api_login,
            'password' => $this->api_password,
            'grant_type' => 'password',
        ];

        $headers = [
            'Content-Type: application/x-www-form-urlencoded',
        ];

        $curl = curl_init();

        if ($customEndpoint) {
            curl_setopt($curl, CURLOPT_URL, 'http://example.com');
        }

        curl_setopt($curl, CURLOPT_URL, $this->endpoint . $api_method);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($curl, CURLINFO_HEADER_OUT, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_FAILONERROR, true);

        $response = curl_exec($curl);
        $error = curl_error($curl);

        curl_close($curl);

        $resp = null;

        if ($error) {
            \PrestaShopLogger::addLog('Volt - Error: ' . $error, 3);
        } else {
            $response = json_decode($response, true);

            if (array_key_exists('access_token', $response)) {
                $resp = $response['access_token'];
            }

            return $resp;
        }

        return null;
    }
}
