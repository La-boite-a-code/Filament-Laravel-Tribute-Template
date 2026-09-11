# Changelog

All notable changes to `laravel-design` will be documented in this file.

## [Unreleased]

### Fixed
- The theme's `primary` and `gray` tokens are now aliases onto the variables
  Filament publishes from the panel's own color registration, instead of two
  hardcoded ramps living beside it. `->colors([...])` and `->withoutColors()`
  used to recolor Filament's components while LaravelDesign's own rules stayed
  Laravel red, and the registered `gray` (Stone) never matched the warm
  neutral the stylesheet paints with — both halves of the panel now follow the
  same palette.

### Changed
- Palettes carry their full 11-shade OKLCH ramp in `Palette::shades()` and are
  registered with the panel directly, so Filament no longer re-derives
  mid-tones from a single hex anchor. `Palette::hex()` and
  `Palette::cssClass()` are gone.
- Switching palette no longer injects an inline `<script>` to put a
  `.ld-palette-{name}` class on `<body>`; the class and the stylesheet blocks
  behind it are removed. The theme is CSS-only again.

### Removed
- Unreferenced `--color-{laravel,forge,cloud,warm}-*` ramps from `@theme`
  (dead weight: the compiled stylesheet is byte-identical without them).

### Added
- Initial package skeleton based on `filament/plugin-skeleton`.
- `LaravelDesignPlugin` for Filament v5 panel registration.
- `LaravelDesignServiceProvider` (Spatie package-tools) for asset, config,
  views, lang and migration loading.
- Theme entrypoint at `resources/css/index.css` + Vite build pipeline.
- Pest test suite (Orchestra Testbench), PHPStan baseline, Pint config.
- GitHub Actions workflows: tests, PHPStan, Pint auto-fix.
