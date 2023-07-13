<?php

namespace Loqate\ApiConnector\Client;

use Loqate\ApiConnector\Client\Http\HttpClient;
use Loqate\ApiConnector\Utils\API;
use Throwable;

/**
 * Extras class
 */
class Extras
{
    /** @var string ApiKey */
    private $apiKey;

    /** @var HttpClient $httpClient */
    private $httpClient;

    /**
     * Verify constructor
     *
     * @param $apiKey
     */
    public function __construct($apiKey)
    {
        $this->httpClient = new HttpClient();
        $this->apiKey = $apiKey;
    }

    /**
     * Search address using the find endpoint of the Loqate API
     *
     * @param $params
     * @return array
     */
    public function verifyAddress($params)
    {
        $params['Key'] = $this->apiKey;
        $endpoint = API::getEndpoint('address_verify');

        try {
            $response = $this->httpClient->post($endpoint, $params);

            if (is_array($response)) {
                return array_column($response, 'Matches');
            }
        } catch (Throwable $exception) {
            return ['error' => true, 'message' => $exception->getMessage()];
        }

        return ['error' => true, 'message' => 'Unexpected error occurred.'];
    }

    /**
     * Get country by ip
     *
     * @param $params
     * @return array|bool
     */
    public function ipToCountry($params)
    {
        $params['Key'] = $this->apiKey;
        $endpoint = API::getEndpoint('extras_ip2country');
        $response = $this->httpClient->get($endpoint, $params);

        try {
            $response = $this->httpClient->get($endpoint, $params);

            if (isset($response['Items']) && $response['Items']) {
                if (isset($response['Items'][0]['Error'])) {
                    return ['error' => true, 'message' => $response['Items'][0]['Description']];
                }

                return $response['Items'][0] ?? null;
            }
        } catch (Throwable $exception) {
            return ['error' => true, 'message' => $exception->getMessage()];
        }

        return ['error' => true, 'message' => 'Unexpected error occurred.'];
    }
}
