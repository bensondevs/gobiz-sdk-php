<?php

namespace BensonDevs\Gobiz\Facades;

use Illuminate\Support\Facades\Facade;

class Gobiz extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'gobiz';
    }
}