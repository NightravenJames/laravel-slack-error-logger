<?php

namespace Raven\Slack\Services;

use Exception;
use Raven\Slack\Jobs\ProcessRequest;

class SlackService {

    private $enable_error_logging;
    
    public $base_uri;
    public $body;
    public $client;
    public $web_hook;

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