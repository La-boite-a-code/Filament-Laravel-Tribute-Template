<?php

declare(strict_types=1);

use Laboiteacode\LaravelDesign\Enums\Palette;

/*
 * LaravelDesign theme configuration.
 *
 * The whole theme is configured fluently from your Filament PanelProvider:
 *
 *     ->plugin(
 *         LaravelDesignPlugin::make()
 *             ->palette(Palette::Forge)
 *             ->maxContentWidth('full'),
 *     )
 *
 * This config file only exists for projects that prefer environment-driven
 * defaults. Publish it with:
 *   php artisan vendor:publish --tag="laravel-design-config"
 */
return [

    /*
    |--------------------------------------------------------------------------
    | Default palette
    |--------------------------------------------------------------------------
    |
    | Brand palette used when ->palette() is not called explicitly.
    | Accepts a Palette enum case, or the string value of one — useful when
    | driving the choice from an env variable.
    |
    | Supported values: 'laravel' (red), 'forge' (teal), 'cloud' (blue).
    |
    */

    'palette' => env('LARAVEL_DESIGN_PALETTE', Palette::Laravel->value),

];
