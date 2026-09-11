<?php

declare(strict_types=1);

namespace Laboiteacode\LaravelDesign;

use Closure;
use Filament\Contracts\Plugin;
use Filament\Panel;
use Filament\Support\Colors\Color;
use Laboiteacode\LaravelDesign\Enums\Palette;

class LaravelDesignPlugin implements Plugin
{
    /**
     * The warm-neutral ramp the whole stylesheet is drawn against. Registered
     * as the panel's `gray` so Filament's own components and LaravelDesign's
     * rules read from the same neutral — Filament emits it on `:root` as
     * `--gray-{shade}`.
     *
     * @var array<int, string>
     */
    protected const GRAY_SHADES = [
        50 => 'oklch(0.990 0.003 75)',
        100 => 'oklch(0.974 0.004 75)',
        200 => 'oklch(0.937 0.005 75)',
        300 => 'oklch(0.882 0.006 75)',
        400 => 'oklch(0.730 0.007 75)',
        500 => 'oklch(0.580 0.008 75)',
        600 => 'oklch(0.460 0.008 75)',
        700 => 'oklch(0.355 0.007 75)',
        800 => 'oklch(0.255 0.006 75)',
        900 => 'oklch(0.165 0.005 75)',
        950 => 'oklch(0.082 0.004 75)',
    ];

    /**
     * @var array<string, mixed>
     */
    protected array $colors = [];

    protected bool $registerColors = true;

    protected ?string $font = 'Instrument Sans';

    protected bool|string $maxContentWidth = false;

    protected ?Palette $palette = null;

    public function getId(): string
    {
        return 'laravel-design';
    }

    public function register(Panel $panel): void
    {
        if ($this->registerColors) {
            $panel->colors($this->resolveColors($this->resolvePalette()));
        }

        if ($this->font !== null) {
            $panel->font($this->font);
        }

        if ($this->maxContentWidth !== false) {
            $panel->maxContentWidth($this->maxContentWidth === true ? 'full' : $this->maxContentWidth);
        }
    }

    /**
     * Resolve the active palette: an explicit `->palette()` call wins; if
     * not provided, fall back to the published config value (driven by the
     * `LARAVEL_DESIGN_PALETTE` env var); if still missing, default to Laravel.
     */
    protected function resolvePalette(): Palette
    {
        if ($this->palette instanceof Palette) {
            return $this->palette;
        }

        $configured = (string) config('laravel-design.palette', Palette::Laravel->value);

        return Palette::tryFrom($configured) ?? Palette::Laravel;
    }

    public function boot(Panel $panel): void
    {
        //
    }

    public static function make(): static
    {
        return app(static::class);
    }

    public static function get(): static
    {
        /** @var static $plugin */
        $plugin = filament(app(static::class)->getId());

        return $plugin;
    }

    /**
     * Pick the brand palette: Laravel (default), Forge, or Cloud.
     */
    public function palette(Palette $palette): static
    {
        $this->palette = $palette;

        return $this;
    }

    /**
     * Override the color palette applied to the panel.
     *
     * @param  array<string, mixed>  $colors
     */
    public function colors(array $colors): static
    {
        $this->colors = $colors;

        return $this;
    }

    /**
     * Disable LaravelDesign's default color palette injection (let the
     * panel keep its own ->colors([...]) configuration untouched).
     */
    public function withoutColors(): static
    {
        $this->registerColors = false;

        return $this;
    }

    public function font(?string $font): static
    {
        $this->font = $font;

        return $this;
    }

    /**
     * Force a specific max content width on the panel. Pass `true` for
     * 'full', a string for any Filament max-width preset, or `false` to
     * keep the panel's own configuration.
     */
    public function maxContentWidth(bool|string $value = true): static
    {
        $this->maxContentWidth = $value;

        return $this;
    }

    /**
     * Default Laravel-inspired palette resolved from the active brand.
     *
     * The ramps are passed through verbatim — Filament re-publishes them on
     * `:root` as `--primary-{shade}` / `--gray-{shade}`, which is exactly what
     * the stylesheet reads. Handing it a single hex anchor instead would let
     * Filament derive its own mid-tones and desynchronise the two.
     *
     * @return array<string, mixed>
     */
    protected function resolveColors(Palette $palette): array
    {
        if ($this->colors !== []) {
            return $this->collapseClosures($this->colors);
        }

        return [
            'primary' => $palette->shades(),
            'gray' => static::GRAY_SHADES,
            'info' => Color::Sky,
            'success' => Color::Emerald,
            'warning' => Color::Amber,
            'danger' => Palette::Laravel->shades(),
        ];
    }

    /**
     * @param  array<string, mixed>  $values
     * @return array<string, mixed>
     */
    protected function collapseClosures(array $values): array
    {
        return array_map(
            static fn (mixed $value): mixed => $value instanceof Closure ? $value() : $value,
            $values,
        );
    }
}
