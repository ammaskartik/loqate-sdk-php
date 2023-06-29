<?php

namespace Loqate\ApiConnector\Client;

use Loqate\ApiConnector\Client\Http\HttpClient;
use Loqate\ApiConnector\Utils\API;
use Throwable;

/**
 * Verify class
 */
class Verify
{
    const VALID = 'Valid';
    const INVALID = 'Invalid';
    const VALID_CATCH_ALL = 'Valid_CatchAll';
    const ACCEPT_VALID_CATCH_ALL = 'AcceptValidCatchAll';

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
     * Verify email address
     *
     * @param $params
     * @return array|bool
     */
    public function verifyEmail($params)
    {
        $params['Key'] = $this->apiKey;
        $endpoint = API::getEndpoint('email_verification');

        try {
            $response = $this->httpClient->get($endpoint, $params);

            if (isset($response['Items']) && $item = reset($response['Items'])) {
                if (isset($params[self::ACCEPT_VALID_CATCH_ALL])) {
                    return $item['ResponseCode'] === self::VALID_CATCH_ALL || $item['ResponseCode'] === self::VALID;
                }

                return $item['ResponseCode'] === self::VALID;
            }
        } catch (Throwable $exception) {
            return ['error' => true, 'message' => $exception->getMessage()];
        }

        return ['error' => true, 'message' => 'Unexpected error occurred.'];
    }

    /**
     * Verify phone number
     *
     * @param $params
     * @return array|bool
     */
    public function verifyPhone($params)
    {
        $params['Key'] = $this->apiKey;
        $endpoint = API::getEndpoint('phone_verification');

        try {
            $response = $this->httpClient->get($endpoint, $params);

            if (isset($response['Items']) && $item = reset($response['Items'])) {
                return $item['IsValid'] === 'Yes';
            }
        } catch (Throwable $exception) {
            return ['error' => true, 'message' => $exception->getMessage()];
        }

        return ['error' => true, 'message' => 'Unexpected error occurred.'];
    }
}
