<?php

namespace Raven\Slack\Providers;

use Illuminate\Support\ServiceProvider;
use Raven\Slack\Services\SlackService;

class SlackServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../../config/slack.php' => config_path('slack.php'),
        ],'raven-slack');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('Slack', function ($app) {
            $slack = new SlackService(config('slack.base_uri'));
            $slack->web_hook = config('slack.error_web_hook');
            return $slack;
        });
    }
}
