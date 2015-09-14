<?php namespace Robot\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Illuminate\View\Factory
 */
class JsonView extends Facade {

    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return new \Robot\Http\JsonResponse;
    }

}
