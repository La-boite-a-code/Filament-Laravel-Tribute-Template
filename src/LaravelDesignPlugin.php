<?php

declare(strict_types=1);

namespace Laboiteacode\LaravelDesign;

use Closure;
use Filament\Contracts\Plugin;
use Filament\Panel;
use Filament\Support\Colors\Color;
use Filament\View\PanelsRenderHook;
use Illuminate\Support\HtmlString;
use Laboiteacode\LaravelDesign\Enums\Palette;

class LaravelDesignPlugin implements Plugin
{
    /**
     * @var array<string, mixed>
     */
    protected array $colors = [];

    protected bool $registerColors = true;

    protected ?string $font = 'Instrument Sans';

    protected bool $maxContentWidth = false;

    protected ?Palette $palette = null;

    public function getId(): string
    {
        return 'laravel-design';
    }

    public function register(Panel $panel): void
    {
        $palette = $this->resolvePalette();

        if ($this->registerColors) {
            $panel->colors($this->resolveColors($palette));
        }

        if ($this->font !== null) {
            $panel->font($this->font);
        }

        if ($this->maxContentWidth !== false) {
            $panel->maxContentWidth($this->maxContentWidth === true ? 'full' : $this->maxContentWidth);
        }

        if ($palette !== Palette::Laravel) {
            $cssClass = $palette->cssClass();

            $panel->renderHook(
                PanelsRenderHook::BODY_START,
                fn (): HtmlString => new HtmlString(
                    "<script>document.body.classList.add('{$cssClass}');</script>"
                ),
            );
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
     * @return array<string, mixed>
     */
    protected function resolveColors(Palette $palette): array
    {
        if ($this->colors !== []) {
            return $this->collapseClosures($this->colors);
        }

        return [
            'primary' => Color::hex($palette->hex()),
            'gray' => Color::Stone,
            'info' => Color::Sky,
            'success' => Color::Emerald,
            'warning' => Color::Amber,
            'danger' => Color::hex(Palette::Laravel->hex()),
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
