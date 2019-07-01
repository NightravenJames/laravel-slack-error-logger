# Slack Error logger for laravel 5.3 to 5.5

## Simple slack error logger package for lower laravel packages

### Note versions of laravel higher thatn _*5.5*_ already have this functionality inbuilt within them; so use this package when looking for a quick patch for this functionality.Otherwise update your laravel version.

#### Also it would be a good idea to switch your queue driver from `QUEUE_DRIVER=sync` to `QUEUE_DRIVER=redis` when in a production environment.

1. _Install_ `composer require nevar/laravel-slack-error-logger "@dev"`,

2. _Service Provider_ depending on your version of laravel copy and the following *code* to `config/app.php`,

```php
return [
    /*
    * Application Service Providers...
    */
    Raven\Slack\Providers\SlackServiceProvider::class,
];
```
*then run* `php artisan config:cache`.

3. _Configurations_ run `php artisan vendor:publish --tag=raven-slack-error-logger` to publish the configuration file `config\slack.php` which should look like this.
```php
return [
    /**
     * ---------------------------------------------------------------------------------------------------
     * slack base uri
     * ---------------------------------------------------------------------------------------------------
     */
    'base_uri' => 'https://hooks.slack.com',

    /**
     * ---------------------------------------------------------------------------------------------------
     * Enable\disable the error logger default is *true*
     * ---------------------------------------------------------------------------------------------------
     */
    'enable_error_logging' => env('SLACK_ENABLE_ERROR_LOGGING',true),

    /**
     *  ---------------------------------------------------------------------------------------------------
     * Your slack channel web hook visit https://api.slack.com/incoming-webhooks for more
     * information on how to acquire one
     *  ---------------------------------------------------------------------------------------------------
     */
    'error_web_hook' => env('SLACK_ERROR_WEBHOOK','/services/ABCD/EFGH/ijklmnopqrst')
];
```
*then override with your own settings by adding* `SLACK_ENABLE_ERROR_LOGGING` and `SLACK_ERROR_WEBHOOK`


4. _Alias_ add the following alias to to `config/app.php`

```php
return [
    /*
    |--------------------------------------------------------------------------
    | Class Aliases
    |--------------------------------------------------------------------------
    |
    | This array of class aliases will be registered when this application
    | is started. However, feel free to register as many as you wish as
    | the aliases are "lazy" loaded so they don't hinder performance.
    |
     */
    'Slack'        => Raven\Slack\Facades\Slack::class,
];
```
*then run* `php artisan config:cache` *again*.

5. _Implementation_ add the following line of code to your exception hander within `app\Exceptions\Handler.php`

```php
    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        \Slack::log_error($exception);
        parent::report($exception);
    }
```



