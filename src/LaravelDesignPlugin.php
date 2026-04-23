<?php

declare(strict_types=1);

namespace Laboiteacode\LaravelDesign;

use Closure;
use Filament\Contracts\Plugin;
use Filament\Panel;
use Filament\Support\Colors\Color;

class LaravelDesignPlugin implements Plugin
{
    /**
     * Laravel's signature red used as the primary accent color.
     */
    public const string LARAVEL_RED = '#FF2D20';

    /**
     * Brand colors per Filament color slot. Resolved lazily so consumers
     * may pass closures that depend on application state.
     *
     * @var array<string, mixed>
     */
    protected array $colors = [];

    protected bool $registerColors = true;

    protected ?string $font = 'Instrument Sans';

    protected bool $compact = false;

    protected bool $maxContentWidth = false;

    public function getId(): string
    {
        return 'laravel-design';
    }

    public function register(Panel $panel): void
    {
        if ($this->registerColors) {
            $panel->colors($this->resolveColors());
        }

        if ($this->font !== null) {
            $panel->font($this->font);
        }

        if ($this->maxContentWidth !== false) {
            $panel->maxContentWidth($this->maxContentWidth === true ? 'full' : $this->maxContentWidth);
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
     * Toggle compact density (smaller paddings on cards/tables/inputs).
     */
    public function compact(bool $condition = true): static
    {
        $this->compact = $condition;

        return $this;
    }

    public function isCompact(): bool
    {
        return $this->compact;
    }

    /**
     * Force a specific max content width on the panel. Pass `true` for
     * 'full', a string for any Filament max-width preset, or `false` to
     * keep the panel's own configuration.
     */
    public function maxContentWidth(bool | string $value = true): static
    {
        $this->maxContentWidth = $value;

        return $this;
    }

    /**
     * Default Laravel-inspired palette.
     *
     * @return array<string, mixed>
     */
    protected function resolveColors(): array
    {
        if ($this->colors !== []) {
            return $this->collapseClosures($this->colors);
        }

        return [
            'primary' => Color::hex(self::LARAVEL_RED),
            'gray' => Color::Stone,
            'info' => Color::Sky,
            'success' => Color::Emerald,
            'warning' => Color::Amber,
            'danger' => Color::hex(self::LARAVEL_RED),
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
