<?php namespace Nikapps\BazaarApiLaravel;

use Illuminate\Support\Facades\Facade;

class BazaarApiFacade extends Facade
{
    /**
     * The name of the binding in the IoC container.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'BazaarApi';
    }
}
