<?php

namespace Raven\Slack\Services;

use Exception;
use Raven\Slack\Jobs\ProcessRequest;

class SlackService {

    private $enable_error_logging;
    
    /**
     * Slack base_uri
     * 
     * @var string $base_uri
     */
    public $base_uri;

    /**
     * Data body
     * 
     * @var array $body
     */
    public $body;

    /**
     * Client configurations for slack request data
     * 
     * @var array $client
     */
    public $client;

    /**
     * Slack channel webhook 
     * 
     * @var string $web_hook
     */
    public $web_hook;

    /**
     * Slack Service instantiation
     * 
     * @param string $base_uri
     */
    public function __construct($base_uri) {
        $this->base_uri = $base_uri;
        $this->enable_error_logging = config('slack.enable_error_logging');
        $this->client = [
            'base_uri' => $this->base_uri,
            'headers' => [
                'Content-Type' => 'application/json'
            ],
        ];
    }

    /**
     * Queue and log the error that occured to the slack channel
     * 
     * @param Exception $exception
     */
    public function log_error(Exception $exception)
    {
        $message = $exception;
        $message = json_encode([
            'message' => $exception->getMessage(),
            'code' => $exception->getCode(),
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
            'user' => \Auth::user(),
        ]);
        $body = $this->body = [
            'text' => "`$message`",
        ];
        dispatch(new ProcessRequest($body,$this->client,$this->web_hook));
    }

}