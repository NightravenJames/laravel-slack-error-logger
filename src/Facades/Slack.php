<?php

namespace Raven\Slack\Facades;

use Illuminate\Support\Facades\Facade;

class Slack extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return class
     */
    protected static function getFacadeAccessor() { return resolve('Slack'); }
}