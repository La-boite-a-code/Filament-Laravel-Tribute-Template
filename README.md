# LaravelDesign

A free Filament v5 theme paying tribute to the laravel.com art
direction — warm-neutral grays, double-frame surfaces, Instrument Sans
typography, three brand palettes (Laravel · Forge · Cloud).

First in a series of homage themes by
[La Boite à Code](https://laboiteacode.fr).

---

## Requirements

- PHP `^8.2`
- Laravel `^12.0`
- Filament `^5.0`
- Tailwind CSS `^4.0` (shipped with Filament v5)

---

## Installation

### 1. Pull the package

```bash
composer require laboiteacode/laravel-design
```

### 2. Run the installer

```bash
php artisan laravel-design:install
```

This publishes:
- `config/laravel-design.php` — optional env-driven palette default
- `resources/css/filament/admin/theme.css` — Filament panel theme entry-point

### 3. Wire the theme into your panel

Edit `app/Providers/Filament/AdminPanelProvider.php` and register both the
theme stylesheet and the plugin:

```php
use Laboiteacode\LaravelDesign\Enums\Palette;
use Laboiteacode\LaravelDesign\LaravelDesignPlugin;

public function panel(Panel $panel): Panel
{
    return $panel
        ->viteTheme('resources/css/filament/admin/theme.css')
        ->plugin(
            LaravelDesignPlugin::make()
                ->palette(Palette::Laravel),
        );
}
```

### 4. Build the assets

```bash
npm run build      # production
npm run dev        # development (HMR)
```

Reload the panel and the theme is live.

---

## Picking a palette

Three brand palettes ship out of the box. Each one swaps the panel's
`primary` color and applies a `.ld-palette-{name}` class on `<body>` so the
CSS can remap `--fi-color-primary-*` tokens consistently.

```php
use Laboiteacode\LaravelDesign\Enums\Palette;

LaravelDesignPlugin::make()->palette(Palette::Laravel) // #f53003 (default)
LaravelDesignPlugin::make()->palette(Palette::Forge)   // #18b69b
LaravelDesignPlugin::make()->palette(Palette::Cloud)   // #0057ff
```

Cards, callouts, sidebar active states, focus rings, primary buttons,
notification dots and pagination chips all follow the chosen palette
automatically — no further configuration needed.

### Driving the palette from env

The plugin reads `config('laravel-design.palette')` automatically when
`->palette()` is not called explicitly. Set the env var and the panel
follows — no extra wiring needed:

```dotenv
LARAVEL_DESIGN_PALETTE=cloud
```

Calling `->palette(Palette::Forge)` always wins over the env value.

---

## Plugin API

All options are fluent and can be chained on `LaravelDesignPlugin::make()`.

| Method | Purpose | Default |
| --- | --- | --- |
| `palette(Palette $palette)` | Pick the brand palette | `Palette::Laravel` |
| `font(?string $font)` | Override the panel font (`null` to leave Filament's choice) | `'Instrument Sans'` |
| `maxContentWidth(bool\|string $value = true)` | `true` for `'full'`, any Filament preset string, or `false` to keep the panel's setting | `false` |
| `colors(array $colors)` | Replace the auto-resolved color array with your own (`Color::hex()` / Filament palettes) | auto |
| `withoutColors()` | Skip color injection entirely — keep the panel's `->colors()` untouched | enabled |

### Example — full configuration

```php
use Filament\Support\Colors\Color;
use Laboiteacode\LaravelDesign\Enums\Palette;
use Laboiteacode\LaravelDesign\LaravelDesignPlugin;

LaravelDesignPlugin::make()
    ->palette(Palette::Forge)
    ->font('Inter')
    ->maxContentWidth('full')
    ->colors([
        'primary' => Color::hex('#7c3aed'),
        'gray' => Color::Slate,
    ]);
```

---

## What the theme styles

Out of the box, LaravelDesign restyles every Filament v5 surface to match
the laravel.com art direction:

- **Sections & cards** — double-frame (inner border + canvas gap + outer
  hairline ring), generous padding, soft 8px radius
- **Tables** — flat header band, hairline row dividers, primary-tinted hover,
  refined pagination
- **Forms** — inputs with red required marks, focus glow, fieldset framing,
  reactive validation states
- **Notifications** — toasts with severity-tinted left edge; database panel
  reads as a clean list with the unread dot in primary color
- **Sidebar** — tinted gradient active state with a primary edge bar,
  primary-filled badges on the current item, group titles with refined
  tracking; the active treatment auto-simplifies to a colored icon when
  the sidebar is collapsed to icons-only
- **Modals** — header/content/footer mirror the section structure for
  visual consistency
- **Buttons** — solid primary, ghost gray, focus glow at the panel's primary
- **Tabs** — segmented pill on contained tabs, underlined inline tabs
- **Page headers** — bold display titles with a primary-colored period accent

A subtle radial gradient is painted on the body canvas so sections lift off
the page without needing a hard background fill.

---

## Customizing tokens

LaravelDesign exposes a handful of CSS custom properties so you can tweak
geometry without rewriting selectors. Add overrides in your panel's theme
CSS, **after** the package import:

```css
@import '../../../../vendor/filament/filament/resources/css/theme.css';
@import '../../../../vendor/laboiteacode/laravel-design/resources/css/index.css';

/* Your overrides */
:root {
    --ld-radius-sm: 0.25rem;     /* tighter buttons */
    --ld-radius:    0.625rem;    /* softer cards */
    --ld-frame-gap: 4px;         /* wider double-frame gap */
}

.dark {
    --ld-frame-ring-color: oklch(0.30 0.005 75);
}
```

Available tokens:

| Token | Role |
| --- | --- |
| `--ld-radius-xs` / `-sm` / `--ld-radius` / `-lg` | Corner radius scale |
| `--ld-pill` | Pill radius (badges, segmented tabs) |
| `--ld-stroke` | Default border thickness |
| `--ld-border-soft-light` / `-dark` | Hairline divider colour, per mode |
| `--ld-canvas` | Body canvas colour |
| `--ld-frame-gap` | Gap between inner border and outer ring |
| `--ld-frame-gap-color` | Gap fill colour (defaults to canvas) |
| `--ld-frame-ring-color` | Outer ring colour |
| `--ld-shadow-sm` / `--ld-shadow-lg` | Elevation scale |
| `--ld-glow-mix` | Focus glow intensity (0–100%) |
| `--ld-duration-slow` | Motion timings |
| `--ld-ease-out` | Motion curve |


---

## How it's compiled

LaravelDesign ships only source CSS — no precompiled bundle. The host
app's Vite/Tailwind pipeline picks up the package's
`resources/css/index.css` through the theme entrypoint published to
`resources/css/filament/admin/theme.css`, alongside Filament's own theme
import. That keeps Tailwind's `@source` scanning aware of both your panel
classes and the package's selectors, so unused utilities are pruned in
your build like any other dependency.

---

## Credits

- Built on top of [`filamentphp/plugin-skeleton`](https://github.com/filamentphp/plugin-skeleton).
- Maintained by [La Boite à Code](https://laboiteacode.fr).

---

## License

MIT — see [LICENSE.md](./LICENSE.md).
