<?php

declare(strict_types=1);

namespace GoldSpecDigital\VoodooSmsSdk;

use GoldSpecDigital\VoodooSmsSdk\Exceptions\ExternalReferenceTooLongException;
use GoldSpecDigital\VoodooSmsSdk\Exceptions\NotValidUkNumberException;
use GoldSpecDigital\VoodooSmsSdk\Exceptions\MessageTooLongException;
use GoldSpecDigital\VoodooSmsSdk\Responses\DeliveryStatusResponse;
use GoldSpecDigital\VoodooSmsSdk\Responses\SendSmsResponse;
use GuzzleHttp\Client as HttpClient;
use InvalidArgumentException;

class Client
{
    protected const URI = 'https://api.voodoosms.com/';
    protected const MESSAGE_LIMIT = 160;
    protected const EXTERNAL_REFERENCE_LIMIT = 30;
    protected const COUNTRY_CODE = 44;
    protected const RESPONSE_FORMAT = 'JSON';
    protected const ACCEPT = 'application/json';

    /**
     * @var \GuzzleHttp\Client
     */
    protected $httpClient;

    /**
     * @var string
     */
    protected $api_key;

    /**
     * @var string
     */
    protected $from;

        /**
     * @var bool
     */
    protected $sandbox;

    /**
     * Client constructor.
     *
     * @param string $api_key
     * @param null|string $from
     */
    public function __construct(string $api_key, string $from = null, $sandbox = false)
    {
        $headers = [
            'Authorization' => 'Bearer ' . $api_key,        
            'Accept'        => static::ACCEPT,
        ];

        $this->httpClient = new HttpClient([
            'base_uri' => static::URI,
            'headers' => $headers,
        ]);

        $this->api_key = $api_key;
        $this->from = $from;
        $this->sandbox = $sandbox;

    }

    /**
     * Send an SMS.
     *
     * @param string $message
     * @param string $to
     * @param null|string $from
     * @param null|string $externalReference The external reference.
     * @return \GoldSpecDigital\VoodooSmsSdk\Responses\SendSmsResponse
     * @throws \InvalidArgumentException
     * @throws \GoldSpecDigital\VoodooSmsSdk\Exceptions\MessageTooLongException
     * @throws \GoldSpecDigital\VoodooSmsSdk\Exceptions\ExternalReferenceTooLongException
     */
    public function send(string $message, string $to, string $from = null, string $externalReference = null): SendSmsResponse
    {
        if (strlen($message) > static::MESSAGE_LIMIT) {
            throw new MessageTooLongException();
        }

        if ($from === null && $this->from === null) {
            throw new InvalidArgumentException('The from parameter must be set.');
        }

        if (is_string($externalReference) && strlen($externalReference) > static::EXTERNAL_REFERENCE_LIMIT) {
            throw new ExternalReferenceTooLongException();
        }

        //REGEX match for a typical UK number 
        if (!preg_match('/^(\+?44\s?7\d{3}|\(?07\d{3}\)?)\s?\d{3}\s?\d{3}$/', $to)) {
            throw new NotValidUkNumberException();
        }

        $to = $this->formatPhoneNumber($to);

        $uri = 'sendsms';
        $parameters = [
            // Required parameters.
            'to' => $to,
            'from' => $from ?? $this->from,
            'msg' => $message,
            'validity' => 1,
            'format' => static::RESPONSE_FORMAT,

            // Optional parameters.
            'cc' => static::COUNTRY_CODE,
            'eref' => $externalReference,
            'sandbox' => $this->sandbox
        ];
        $parameters = array_filter($parameters, [$this, 'isNotNull']);

        $response = $this->httpClient->post($uri, ['form_params' => $parameters]);
        $responseContents = json_decode((string)$response->getBody(), true);

        return new SendSmsResponse($responseContents, $response->getStatusCode());
    }

    /**
     * @param string $messageId
     * @return \GoldSpecDigital\VoodooSmsSdk\Responses\DeliveryStatusResponse
     */
    public function getDeliveryStatus(string $messageId): DeliveryStatusResponse
    {
        $uri = 'report';
        $parameters = [
            'message_id' => $messageId,
        ];

        $response = $this->httpClient->get($uri, ['form_params' => $parameters]);
        $responseContents = json_decode((string)$response->getBody(), true);

        return new DeliveryStatusResponse($responseContents, $response->getStatusCode());
    }

    /**
     * @param $to
     * @return string
     */
    protected function formatPhoneNumber(string $to): string 
    {
        if(str_starts_with($to, "+44")) {
            return str_replace("+", "", $to);
        }

        if(str_starts_with($to, "07")) {
            return str_replace("07", "447", $to);
        }

        return $to;
    }

    /**
     * @param $value
     * @return bool
     */
    protected function isNotNull($value): bool
    {
        return $value !== null;
    }
}
