# Changelog

All notable changes to `filament-laravel-tribute-template` will be documented in this file.

## [Unreleased]

### Changed

- Renamed the package to **Filament Laravel Tribute Template**: composer name
  `laboiteacode/filament-laravel-tribute-template`, namespace
  `LaBoiteACode\LaravelTributeTemplate`, config file
  `config/filament-laravel-tribute-template.php`, install command
  `laravel-tribute-template:install`.
- The stylesheet now reads Filament's own palette variables (`--primary-*`,
  `--gray-*`, `--danger-*`, `--success-*`, `--warning-*`, `--info-*`) instead of
  declaring a private `--fi-color-*` scale. Palettes are registered on the panel
  through `->colors()`, so the body-class switcher and its injected script are
  gone, along with about 160 lines of duplicated shade scales.
- Palettes now ship a full hand-tuned oklch shade scale per brand instead of a
  single anchor colour, so Filament's contrast-aware colour maps keep each
  product's look: white text on Laravel red and Cloud blue, dark text on the
  bright Forge green. Forge is anchored on its published `#02EAC2`.
- `colors()` merges over the active palette instead of replacing it, accepts
  Filament colour names and `rgb()` / `oklch()` strings, and resolves closures
  lazily at render time.
- `maxContentWidth()` accepts a `Filament\Support\Enums\Width` case.
- Twelve dead rules were repointed onto real Filament hooks: fieldsets, field
  hints and helper texts, schema containers, button icons, table footer, header
  cell labels, text column items, page sub-navigation tabs and stat description
  icons all targeted class names Filament never renders.

### Added

- `sidebarWidth()` on the plugin, plus `font`, `sidebar_width` and `colors`
  keys in the config file.
- `tests/PluginTest.php` covering palette resolution, config precedence,
  opt-outs, lazy closures and the contrast maps of the three brands.
- `tests/ThemeCssTest.php`, which fails if the stylesheet targets a `.fi-*`
  class the installed Filament does not render, or reintroduces a private
  colour scale.
- Press kit under `art/`: banner and thumbnail, plus the HTML they are
  rendered from.

### Removed

- The `.ld-palette-*` body classes, the `PanelsRenderHook` that injected them
  and the `cssClass()` method on the palette enum.
- The unused brand shade scales declared in the Tailwind `@theme` block.

## [1.0.0-rc] - 2026-07-08

### Added

- Branded install command, published `theme.css` entry point, palette enum and
  the full component pass over Filament v5 surfaces.
