<?php

declare(strict_types=1);

namespace Laboiteacode\LaravelDesign\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Laboiteacode\LaravelDesign\LaravelDesign
 */
class LaravelDesign extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Laboiteacode\LaravelDesign\LaravelDesign::class;
    }
}
