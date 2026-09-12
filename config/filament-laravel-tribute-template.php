<?php

declare(strict_types=1);

use LaBoiteACode\LaravelTributeTemplate\Enums\Palette;

/*
 * Filament Laravel Tribute Template configuration.
 *
 * The theme is usually configured fluently from your Filament PanelProvider:
 *
 *     ->plugin(
 *         LaravelTributeTemplatePlugin::make()
 *             ->palette(Palette::Forge)
 *             ->maxContentWidth(),
 *     )
 *
 * This file exists for projects that prefer environment-driven defaults.
 * Publish it with:
 *   php artisan vendor:publish --tag="filament-laravel-tribute-template-config"
 */
return [

    /*
    |--------------------------------------------------------------------------
    | Default palette
    |--------------------------------------------------------------------------
    |
    | Brand palette used when ->palette() is not called explicitly. Each one
    | is a full oklch shade scale anchored on the product's official colour:
    | 'laravel' (red #F53003), 'forge' (green #02EAC2), 'cloud' (blue #0057FF).
    |
    */

    'palette' => env('LARAVEL_TRIBUTE_TEMPLATE_PALETTE', Palette::Laravel->value),

    /*
    |--------------------------------------------------------------------------
    | Palette overrides
    |--------------------------------------------------------------------------
    |
    | Optional per-slot overrides merged over the palette: primary, gray,
    | danger, info, success, warning. Accepts a Filament colour name
    | ("stone", "sky", ...), any colour string Filament understands
    | ("#F53003", "rgb(...)", "oklch(...)") or a shade array.
    |
    */

    'colors' => [],

    /*
    |--------------------------------------------------------------------------
    | Typography
    |--------------------------------------------------------------------------
    |
    | Font family applied through Filament's ->font(). Instrument Sans is the
    | typeface used on laravel.com. Set to null to keep the panel's own font.
    |
    */

    'font' => 'Instrument Sans',

    /*
    |--------------------------------------------------------------------------
    | Layout
    |--------------------------------------------------------------------------
    |
    | Sidebar width passed to Filament's ->sidebarWidth() (e.g. "18rem").
    | Null keeps Filament's default.
    |
    */

    'sidebar_width' => null,

];
