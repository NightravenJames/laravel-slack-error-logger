# Slack Error logger for laravel 5.3 to 5.5

## Simple slack error logger package for lower laravel packages

### Note versions of laravel higher thatn _*5.5*_ already have this functionality inbuilt within them; so use this package when looking for a quick patch for this functionality.Otherwise update your laravel version.

#### Also it would be a good idea to switch your queue driver from `QUEUE_DRIVER=sync` to `QUEUE_DRIVER=redis` when in a production environment.

1. _Install_ `$ composer require nevar/laravel-slack-error-logger "@dev"`,

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

3. _Alias_ add the following alias to to `config/app.php`

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

4. _Implementation_ add the following line of code to your exception hander within `app\Exceptions\Handler.php`

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



