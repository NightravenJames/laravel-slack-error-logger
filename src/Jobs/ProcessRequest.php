<?php

namespace Raven\Slack\Jobs;

use GuzzleHttp\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Raven\Slack\Services\SlackService;

class ProcessRequest implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    protected $body,$client,$route;


    public function __construct($body,$client,$route)
    {
        $this->client = $client;
        $this->body = $body;
        $this->route = $route;

    }

    
    public function handle()
    {
        $client = new Client($this->client);
        return $client->request('POST',$this->route,[
            'json' => $this->body,
        ]);
    }
}