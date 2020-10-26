<?php

namespace Eval4VictoryCTO\LaravelImageUploader\Facades;

use Illuminate\Support\Facades\Facade;

class ImageUploader extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'image-uploader';
    }
}
