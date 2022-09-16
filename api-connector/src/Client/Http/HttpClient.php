<?php

namespace Loqate\ApiConnector\Client\Http;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

/**
 * HttpClient class
 */
class HttpClient
{
    /** @var Client $httpClient */
    private $httpClient;

    /**
     * HttpClient constructor
     *
     */
    public function __construct()
    {
        $this->httpClient = new Client();
    }

    /**
     * Do GET request
     *
     * @throws GuzzleException
     * @throws Exception
     */
    public function get(string $endpoint, array $params)
    {
        $queryString = http_build_query($params);
        $endpoint .= '?' . $queryString;
        $rawResponse = $this->httpClient->request('GET', $endpoint);

        $response = json_decode($rawResponse->getBody()->getContents(), true);

        if ($errorMessage = $this->searchForError($response)) {
            throw new Exception($errorMessage);
        }

        return $response;
    }

    /**
     * Do POST request
     *
     * @throws GuzzleException
     * @throws Exception
     */
    public function post(string $endpoint, array $params)
    {
        $options = [
            'headers' => ['Content-Type' => 'application/json'],
            'body' => json_encode($params)
        ];
        $rawResponse = $this->httpClient->request('POST', $endpoint, $options);
        $response = json_decode($rawResponse->getBody()->getContents(), true);

        if ($errorMessage = $this->searchForError($response)) {
            throw new Exception($errorMessage);
        }

        return $response;
    }

    /**
     * Check for error in response
     *
     * @param $response
     * @return false|mixed
     */
    private function searchForError($response)
    {
        if (isset($response['Items'][0]['Error'])) {
            return $response['Items'][0]['Description'];
        }

        if (isset($response['Number'])) {
            return $response['Description'];
        }

        return false;
    }
}
