<?php

namespace Raven\Slack\Services;

use Exception;
use GuzzleHttp\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SlackService implements ShouldQueue{
    use InteractsWithQueue, Queueable, SerializesModels;

    private $base_uri,$client,$enable_error_logging,$response;
    public $web_hook;

    public function __construct($base_uri) {
        $this->base_uri = $base_uri;
        $this->enable_error_logging = config('slack.enable_error_logging');
        $this->client = new Client([
            'base_uri' => $this->base_uri,
            'headers' => [
                'Content-Type' => 'application/json'
            ],
        ]);
    }

    public function log_error(Exception $exception)
    {
        $message = json_encode([
            'message' => $exception->getMessage(),
            'code' => $exception->getCode(),
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
            'user' => \Auth::user(),
        ]);
        $this->post($this->web_hook,[
            'text' => "`$message`",
        ]);
    }

    private function post($route,array $body)
    {
        $this->enable_error_logging?$this->handle($route,$body):null;
    }

    public function handle($route,array $body)
    {
        $this->client->request('POST',$route,[
            'json' => $body,
        ]);
    }

}