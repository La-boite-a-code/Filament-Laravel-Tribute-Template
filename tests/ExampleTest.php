<?php

declare(strict_types=1);

use LaBoiteACode\LaravelTributeTemplate\LaravelTributeTemplate;
use LaBoiteACode\LaravelTributeTemplate\LaravelTributeTemplatePlugin;

it('has a version', function (): void {
    expect((new LaravelTributeTemplate)->version())->toBe('1.0.0');
});

it('exposes a plugin id', function (): void {
    expect(LaravelTributeTemplatePlugin::make()->getId())->toBe('filament-laravel-tribute-template');
});
