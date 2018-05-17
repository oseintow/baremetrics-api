<?php
namespace Oseintow\Baremetrics;

use GuzzleHttp\Client;

class Baremetrics
{
    const LIVE_API_URL = 'https://api.baremetrics.com';
    const SANDBOX_API_URL = 'https://api-sandbox.baremetrics.com';
    protected $apiVersion = "v1";
    protected $apiUrl;
    protected $requestHeaders = [];
    protected $responseStatusCode;
    protected $reasonPhrase;
    protected $responseHeaders = [];
    /**
     * @var Client
     */
    private $client;

    public function __construct()
    {
        $this->client = new Client();
        $this->apikey = env("BAREMETRICS_API_KEY") ?? "";
        $this->apiUrl = self::SANDBOX_API_URL;
    }

    public function setApiVersion($apiVersion)
    {
        $this->apiVersion = $apiVersion;

        return $this;
    }

    public function setApiKey($key)
    {
        $this->apikey = $key;

        return $this;
    }

    public function isLiveMode($mode = false)
    {
        if($mode == false) {
            $this->setApiUrl(self::SANDBOX_API_URL);
        }else {
            $this->setApiUrl(self::LIVE_API_URL);
        }

        return $this;
    }

    public function setApiUrl($url)
    {
        $this->apiUrl = $url;

        return $this;
    }

    public function getApiUrl()
    {
        return $this->apiUrl;
    }

    /*
     *  $args[0] is for route uri and $args[1] is either request body or query strings
     */
    public function __call($method, $args)
    {
        list($uri, $params) = [ltrim($args[0],"/"), $args[1] ?? []];
        $response = $this->makeRequest($method, $uri, $params);

        return $response;
    }

    public function makeRequest($method, $uri, $params = [], $headers = [])
    {
        $query = in_array($method, ['get','delete']) ? "query" : "json";

        $this->setHeaders();

        $response = $this->client->request(strtoupper($method), $this->getApiUrl(). "/{$this->apiVersion}/{$uri}", [
            'headers' => array_merge($headers, $this->requestHeaders),
            $query => $params,
            'timeout' => 120.0,
            'connect_timeout' => 120.0,
            'http_errors' => false,
            "verify" => false
        ]);

        $this->parseResponse($response);

        $responseBody = $this->responseBody($response);

        return $responseBody;
    }

    private function setHeaders()
    {
        $this->requestHeaders["Authorization"] = "Bearer " . $this->apikey;
    }

    private function parseResponse($response)
    {
        $this->parseHeaders($response->getHeaders());
        $this->setStatusCode($response->getStatusCode());
        $this->setReasonPhrase($response->getReasonPhrase());
    }

    private function setStatusCode($code)
    {
        $this->responseStatusCode = $code;
    }

    public function getStatusCode()
    {
        return $this->responseStatusCode;
    }

    private function setReasonPhrase($message)
    {
        $this->reasonPhrase = $message;
    }

    public function getReasonPhrase()
    {
        return $this->reasonPhrase;
    }

    private function parseHeaders($headers)
    {
        foreach ($headers as $name => $values) {
            $value = (is_array($values) && !empty($values)) ? $values[0] : $values;

            $this->responseHeaders = array_merge($this->responseHeaders, [$name => $value]);
        }
    }

    public function getHeaders()
    {
        return $this->responseHeaders;
    }

    public function getHeader($header)
    {
        return $this->hasHeader($header) ? $this->responseHeaders[$header] : '';
    }

    public function hasHeader($header)
    {
        return array_key_exists($header, $this->responseHeaders);
    }

    private function responseBody($response)
    {
        return json_decode($response->getBody(), true);
    }
}