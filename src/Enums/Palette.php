<?php

declare(strict_types=1);

namespace Laboiteacode\LaravelDesign\Enums;

/**
 * Brand palette presets shipped with LaravelDesign. Each case carries the
 * official HEX anchor and the CSS class name applied to <body> when the
 * palette is active.
 *
 * Add a new case here to introduce a new palette — the plugin and CSS
 * pick it up automatically as long as a matching `.ld-palette-{value}`
 * block is defined in the theme stylesheet.
 */
enum Palette: string
{
    case Laravel = 'laravel';

    case Forge = 'forge';

    case Cloud = 'cloud';

    /**
     * Brand HEX anchor — sourced from the official Laravel, Forge and
     * Cloud sites. Used to seed the panel's `primary` color.
     */
    public function hex(): string
    {
        return match ($this) {
            self::Laravel => '#f53003',
            self::Forge => '#18b69b',
            self::Cloud => '#0057ff',
        };
    }

    /**
     * The body class consumed by the theme stylesheet to remap the
     * `--fi-color-primary-*` tokens for non-Laravel palettes.
     */
    public function cssClass(): string
    {
        return "ld-palette-{$this->value}";
    }
}
