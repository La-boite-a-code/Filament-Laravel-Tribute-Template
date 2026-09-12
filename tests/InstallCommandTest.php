<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Artisan;

it('registers the branded install command', function (): void {
    $commands = array_keys(Artisan::all());

    expect($commands)->toContain('laravel-tribute-template:install')
        ->and($commands)->not->toContain('design:install');
});
