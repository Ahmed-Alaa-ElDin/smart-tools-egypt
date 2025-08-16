<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class MetaPixel extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'meta-pixel';
    }
}
