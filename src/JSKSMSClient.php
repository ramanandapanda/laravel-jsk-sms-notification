<?php

namespace NotificationChannels\JSKSMS;

use Exception;
use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Exception\ClientException;
use NotificationChannels\JSKSMS\Exceptions\CouldNotSendNotification;

class JSKSMSClient
{
    /**
     * @var string JSKSMS API URL.
     */
    protected string $apiUrl = 'http://jskbulkmarketing.in/app/';

    /**
     * @var HttpClient HTTP Client.
     */
    protected $http;

    /**
     * @var null|string JSKSMS API Key.
     */
    protected $key;

    /**
     * @param string $key
     * @param HttpClient $http
     */
    public function __construct(
        string $key,
        string $entity = '',
        string $tempid = '',
        string $routeid = '',
        string $type = 'text',
        string $senderid = '',
        HttpClient $http = null
    ) {
        $this->key = $key;
        $this->entity = $entity;
        $this->tempid =  $tempid;
        $this->routeid = $routeid;
        $this->type = $type;
        $this->senderid = $senderid;

        $this->http = $http;
    }


    /**
     * Get HttpClient.
     *
     * @return HttpClient
     */
    protected function httpClient(): httpClient
    {
        return $this->http ?? new HttpClient();
    }

    /**
     * Send text message.
     * @param array $params
     */
    public function sendMessage(array $params)
    {
        return $this->sendRequest('smsapi/index.php', $params);
    }

    public function sendRequest(string $endpoint, array $params)
    {
        try {
            return $this->httpClient()->get($this->apiUrl . $endpoint, $params);
        } catch (ClientException $exception) {
            throw CouldNotSendNotification::serviceRespondedWithAnError($exception);
        } catch (Exception $exception) {
            throw CouldNotSendNotification::serviceNotAvailable($exception);
        }
    }
    public function getParams(){
        return [
            'key' => $this->key,
            'entity' => $this->entity,
            'tempid' => $this->tempid,
            'routeid' => $this->routeid,
            'type' => $this->type,
            'senderid' => $this->senderid,
        ];
    }
}
