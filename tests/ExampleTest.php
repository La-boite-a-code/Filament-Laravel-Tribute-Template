<?php

declare(strict_types=1);

use Laboiteacode\LaravelDesign\LaravelDesign;
use Laboiteacode\LaravelDesign\LaravelDesignPlugin;

it('has a version', function (): void {
    expect((new LaravelDesign)->version())->toBe('0.1.0');
});

it('exposes a plugin id', function (): void {
    expect(LaravelDesignPlugin::make()->getId())->toBe('laravel-design');
});
