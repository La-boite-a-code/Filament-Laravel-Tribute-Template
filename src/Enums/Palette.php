<?php

declare(strict_types=1);

namespace Laboiteacode\LaravelDesign\Enums;

/**
 * Brand palette presets shipped with LaravelDesign.
 *
 * Each case carries the hand-tuned 11-shade OKLCH ramp used as the panel's
 * `primary` color. The plugin registers that ramp with Filament, which emits
 * it on `:root` as `--primary-{shade}`; the stylesheet then reads those
 * variables instead of redeclaring the values. One source of truth.
 *
 * Add a new case with its `shades()` entry and the plugin picks it up — no
 * stylesheet change required.
 */
enum Palette: string
{
    case Laravel = 'laravel';

    case Forge = 'forge';

    case Cloud = 'cloud';

    /**
     * The full primary ramp for this palette. Brand anchors, for reference:
     * Laravel `#f53003`, Forge `#18b69b`, Cloud `#0057ff` — expressed here in
     * OKLCH so the mid-tones stay perceptually even instead of being derived
     * algorithmically from the anchor.
     *
     * @return array<int, string>
     */
    public function shades(): array
    {
        return match ($this) {
            self::Laravel => [
                50 => 'oklch(0.971 0.018 22)',
                100 => 'oklch(0.940 0.040 22)',
                200 => 'oklch(0.892 0.080 22)',
                300 => 'oklch(0.820 0.140 23)',
                400 => 'oklch(0.730 0.205 24)',
                500 => 'oklch(0.660 0.250 25)',
                600 => 'oklch(0.590 0.245 26)',
                700 => 'oklch(0.510 0.215 27)',
                800 => 'oklch(0.430 0.180 27)',
                900 => 'oklch(0.370 0.140 26)',
                950 => 'oklch(0.250 0.090 26)',
            ],
            self::Forge => [
                50 => 'oklch(0.971 0.020 175)',
                100 => 'oklch(0.940 0.040 175)',
                200 => 'oklch(0.892 0.075 175)',
                300 => 'oklch(0.820 0.100 175)',
                400 => 'oklch(0.740 0.115 175)',
                500 => 'oklch(0.660 0.115 175)',
                600 => 'oklch(0.590 0.110 175)',
                700 => 'oklch(0.510 0.100 175)',
                800 => 'oklch(0.430 0.085 175)',
                900 => 'oklch(0.370 0.060 175)',
                950 => 'oklch(0.250 0.040 175)',
            ],
            self::Cloud => [
                50 => 'oklch(0.971 0.018 263)',
                100 => 'oklch(0.940 0.040 263)',
                200 => 'oklch(0.880 0.090 263)',
                300 => 'oklch(0.770 0.155 263)',
                400 => 'oklch(0.640 0.215 263)',
                500 => 'oklch(0.510 0.265 263)',
                600 => 'oklch(0.450 0.255 263)',
                700 => 'oklch(0.390 0.225 263)',
                800 => 'oklch(0.320 0.180 263)',
                900 => 'oklch(0.260 0.140 263)',
                950 => 'oklch(0.180 0.090 263)',
            ],
        };
    }
}
