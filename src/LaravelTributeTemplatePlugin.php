<?php

declare(strict_types=1);

namespace LaBoiteACode\LaravelTributeTemplate;

use Closure;
use Filament\Contracts\Plugin;
use Filament\Panel;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\Width;
use Illuminate\Support\Str;
use InvalidArgumentException;
use LaBoiteACode\LaravelTributeTemplate\Enums\Palette;

class LaravelTributeTemplatePlugin implements Plugin
{
    /**
     * Palette overrides per Filament colour slot, merged over the active
     * palette. Values may be a Filament colour name ("stone"), any colour
     * string Filament understands (hex, rgb(), oklch()), a shade array, or
     * a closure returning one of those.
     *
     * @var array<string, mixed>
     */
    protected array $colors = [];

    protected bool $registerColors = true;

    protected Palette|string|null $palette = null;

    /**
     * null = use config, false = keep the panel's own value.
     */
    protected string|false|null $font = null;

    protected string|false|null $sidebarWidth = null;

    protected bool|string|Width $maxContentWidth = false;

    public function getId(): string
    {
        return 'filament-laravel-tribute-template';
    }

    public function register(Panel $panel): void
    {
        if ($this->registerColors) {
            // Deferred so closures in the palette resolve at render time,
            // exactly like Filament's own ->colors(fn () => [...]).
            $panel->colors(fn (): array => $this->resolveColors());
        }

        $font = $this->font ?? $this->configString('font');

        if (is_string($font) && $font !== '') {
            $panel->font($font);
        }

        $sidebarWidth = $this->sidebarWidth ?? $this->configString('sidebar_width');

        if (is_string($sidebarWidth) && $sidebarWidth !== '') {
            $panel->sidebarWidth($sidebarWidth);
        }

        if ($this->maxContentWidth !== false) {
            $panel->maxContentWidth($this->maxContentWidth === true ? Width::Full : $this->maxContentWidth);
        }
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
     * Pick the brand palette: Palette::Laravel (default), Forge or Cloud,
     * or their string value.
     */
    public function palette(Palette|string $palette): static
    {
        $this->palette = $palette;

        return $this;
    }

    /**
     * Override colour slots on top of the palette.
     *
     * @param  array<string, mixed>  $colors
     */
    public function colors(array $colors): static
    {
        $this->colors = $colors;

        return $this;
    }

    /**
     * Keep the panel's own ->colors([...]) configuration untouched.
     */
    public function withoutColors(): static
    {
        $this->registerColors = false;

        return $this;
    }

    /**
     * Override the font family. Pass null to keep the panel's own font.
     */
    public function font(?string $font): static
    {
        $this->font = $font ?? false;

        return $this;
    }

    /**
     * Override the sidebar width. Pass null to keep the panel's own width.
     */
    public function sidebarWidth(?string $width): static
    {
        $this->sidebarWidth = $width ?? false;

        return $this;
    }

    /**
     * Force a max content width on the panel: `true` for full width, a
     * Filament Width or preset string, or `false` to keep the panel's own.
     */
    public function maxContentWidth(bool|string|Width $value = true): static
    {
        $this->maxContentWidth = $value;

        return $this;
    }

    /**
     * The active palette: an explicit ->palette() call wins, then the
     * config value, then Laravel.
     */
    public function resolvePalette(): Palette
    {
        $palette = $this->palette ?? $this->configString('palette') ?? Palette::Laravel;

        if ($palette instanceof Palette) {
            return $palette;
        }

        return Palette::tryFrom(strtolower($palette)) ?? throw new InvalidArgumentException(
            "Unknown palette [{$palette}]. Use 'laravel', 'forge' or 'cloud'.",
        );
    }

    /**
     * Palette colours, overridden by the `colors` config key, overridden by
     * ->colors() on the plugin.
     *
     * @return array<string, mixed>
     */
    protected function resolveColors(): array
    {
        $colors = [
            ...$this->resolvePalette()->colors(),
            ...(array) config('filament-laravel-tribute-template.colors', []),
            ...$this->colors,
        ];

        return array_map(
            fn (mixed $color): mixed => $this->normalizeColor($color),
            $colors,
        );
    }

    /**
     * Map Filament colour names ("stone") to their shade arrays; every other
     * value (hex / rgb() / oklch() strings, shade arrays) is passed through
     * untouched because Filament's ColorManager already converts those.
     */
    protected function normalizeColor(mixed $color): mixed
    {
        if ($color instanceof Closure) {
            $color = $color();
        }

        if (! is_string($color) || preg_match('/^[a-z][a-z0-9-]*$/i', $color) !== 1) {
            return $color;
        }

        $constant = Color::class.'::'.Str::studly($color);

        if (! defined($constant)) {
            throw new InvalidArgumentException(
                "Unknown colour [{$color}]. Use a Filament colour name such as 'stone', or a hex / rgb() / oklch() value.",
            );
        }

        return constant($constant);
    }

    protected function configString(string $key): ?string
    {
        $value = config("filament-laravel-tribute-template.{$key}");

        return is_string($value) ? $value : null;
    }
}
