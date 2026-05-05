<?php

declare(strict_types=1);

namespace Laboiteacode\LaravelDesign;

use Closure;
use Filament\Contracts\Plugin;
use Filament\Panel;
use Filament\Support\Colors\Color;
use Filament\View\PanelsRenderHook;
use Illuminate\Support\HtmlString;

class LaravelDesignPlugin implements Plugin
{
    public const string PALETTE_LARAVEL = 'laravel';

    public const string PALETTE_FORGE = 'forge';

    public const string PALETTE_CLOUD = 'cloud';

    /**
     * Brand HEX anchors per palette — sourced from the official Laravel,
     * Forge and Cloud sites.
     */
    private const PALETTE_HEX = [
        self::PALETTE_LARAVEL => '#f53003',
        self::PALETTE_FORGE => '#18b69b',
        self::PALETTE_CLOUD => '#0057ff',
    ];

    /**
     * @var array<string, mixed>
     */
    protected array $colors = [];

    protected bool $registerColors = true;

    protected ?string $font = 'Instrument Sans';

    protected bool $compact = false;

    protected bool $maxContentWidth = false;

    protected string $palette = self::PALETTE_LARAVEL;

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

        if ($this->palette !== self::PALETTE_LARAVEL) {
            $panel->renderHook(
                PanelsRenderHook::BODY_START,
                fn (): HtmlString => new HtmlString(
                    "<script>document.body.classList.add('ld-palette-{$this->palette}');</script>"
                ),
            );
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
     * Pick the brand palette: laravel (default), forge, or cloud.
     */
    public function palette(string $palette): static
    {
        if (! array_key_exists($palette, self::PALETTE_HEX)) {
            throw new \InvalidArgumentException(
                "Unknown palette [{$palette}]. Use one of: ".implode(', ', array_keys(self::PALETTE_HEX))
            );
        }

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
    protected function resolveColors(): array
    {
        if ($this->colors !== []) {
            return $this->collapseClosures($this->colors);
        }

        $primaryHex = self::PALETTE_HEX[$this->palette];

        return [
            'primary' => Color::hex($primaryHex),
            'gray' => Color::Stone,
            'info' => Color::Sky,
            'success' => Color::Emerald,
            'warning' => Color::Amber,
            'danger' => Color::hex(self::PALETTE_HEX[self::PALETTE_LARAVEL]),
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
