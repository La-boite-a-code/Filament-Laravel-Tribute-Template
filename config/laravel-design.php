<?php

declare(strict_types=1);

/*
 * LaravelDesign theme configuration.
 *
 * Override these values from your application by publishing the config file:
 *   php artisan vendor:publish --tag="laravel-design-config"
 */
return [

    /*
    |--------------------------------------------------------------------------
    | Brand Colors
    |--------------------------------------------------------------------------
    |
    | Default brand color palette used by the theme. Override per-panel
    | through the LaravelDesignPlugin if you need to customize at runtime.
    |
    */

    'colors' => [
        'primary' => 'amber',
        'gray' => 'zinc',
    ],

    /*
    |--------------------------------------------------------------------------
    | Layout Defaults
    |--------------------------------------------------------------------------
    */

    'layout' => [
        'sidebar' => [
            'collapsible' => true,
            'width' => '20rem',
        ],
        'topbar' => [
            'sticky' => true,
        ],
    ],

];
