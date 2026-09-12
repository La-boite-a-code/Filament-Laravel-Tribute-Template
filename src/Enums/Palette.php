<?php

declare(strict_types=1);

namespace LaBoiteACode\LaravelTributeTemplate\Enums;

use Filament\Support\Colors\Color;

/**
 * Brand palettes shipped with the theme, one per Laravel product.
 *
 * Each case carries the official brand colour and a full oklch shade scale
 * built on that hue, with the brand colour pinned on the shade it naturally
 * occupies. The scales are hand-tuned rather than generated: Filament picks
 * button, badge and link shades by contrast, and a generated scale drifts
 * away from the brand (Laravel red turns pink, Forge green loses its dark
 * text). These scales keep each product's own look.
 *
 * The whole theme reads Filament's palette variables, so registering a case
 * through the plugin is enough to recolour every component.
 */
enum Palette: string
{
    case Laravel = 'laravel';

    case Forge = 'forge';

    case Cloud = 'cloud';

    /**
     * Brand colour, as published on laravel.com, laravel.com/forge and
     * laravel.com/cloud.
     */
    public function hex(): string
    {
        return match ($this) {
            self::Laravel => '#F53003',
            self::Forge => '#02EAC2',
            self::Cloud => '#0057FF',
        };
    }

    /**
     * The shade the brand colour is pinned on inside the scale.
     *
     * Shades that Filament may pick as a button background are additionally
     * tuned to clear 4.5:1 against the label it pairs them with.
     */
    public function brandShade(): int
    {
        return match ($this) {
            self::Laravel => 500,
            self::Forge => 400,
            self::Cloud => 600,
        };
    }

    /**
     * Full palette, one entry per Filament colour slot.
     *
     * @return array<string, array<int, string>>
     */
    public function colors(): array
    {
        return match ($this) {
            self::Laravel => [
                'primary' => $this->shades(),
                'danger' => $this->shades(),
                'gray' => Color::Stone,
                'info' => Color::Sky,
                'success' => Color::Emerald,
                'warning' => Color::Amber,
            ],
            self::Forge => [
                'primary' => $this->shades(),
                'danger' => Color::Red,
                'gray' => Color::Neutral,
                'info' => Color::Sky,
                'success' => $this->shades(),
                'warning' => Color::Amber,
            ],
            self::Cloud => [
                'primary' => $this->shades(),
                'danger' => Color::Red,
                'gray' => Color::Slate,
                'info' => $this->shades(),
                'success' => Color::Emerald,
                'warning' => Color::Amber,
            ],
        };
    }

    /**
     * Shade scale for the brand colour itself.
     *
     * @return array<int, string>
     */
    public function shades(): array
    {
        return match ($this) {
            self::Laravel => [
                50 => 'oklch(0.971 0.013 32.358)',
                100 => 'oklch(0.936 0.032 32.358)',
                200 => 'oklch(0.885 0.062 32.358)',
                300 => 'oklch(0.808 0.114 32.358)',
                400 => 'oklch(0.704 0.191 32.358)',
                500 => 'oklch(0.627 0.234 32.358)',
                600 => 'oklch(0.577 0.245 32.358)',
                700 => 'oklch(0.505 0.213 32.358)',
                800 => 'oklch(0.444 0.177 32.358)',
                900 => 'oklch(0.396 0.141 32.358)',
                950 => 'oklch(0.258 0.092 32.358)',
            ],
            self::Forge => [
                50 => 'oklch(0.984 0.014 174.292)',
                100 => 'oklch(0.953 0.051 174.292)',
                200 => 'oklch(0.91 0.096 174.292)',
                300 => 'oklch(0.855 0.138 174.292)',
                400 => 'oklch(0.836 0.158 174.292)',
                500 => 'oklch(0.704 0.14 174.292)',
                600 => 'oklch(0.54 0.118 174.292)',
                700 => 'oklch(0.511 0.096 174.292)',
                800 => 'oklch(0.437 0.078 174.292)',
                900 => 'oklch(0.386 0.063 174.292)',
                950 => 'oklch(0.277 0.046 174.292)',
            ],
            self::Cloud => [
                50 => 'oklch(0.97 0.014 262.466)',
                100 => 'oklch(0.932 0.032 262.466)',
                200 => 'oklch(0.882 0.059 262.466)',
                300 => 'oklch(0.809 0.105 262.466)',
                400 => 'oklch(0.707 0.165 262.466)',
                500 => 'oklch(0.623 0.214 262.466)',
                600 => 'oklch(0.537 0.257 262.466)',
                700 => 'oklch(0.488 0.243 262.466)',
                800 => 'oklch(0.424 0.199 262.466)',
                900 => 'oklch(0.379 0.146 262.466)',
                950 => 'oklch(0.282 0.091 262.466)',
            ],
        };
    }
}
