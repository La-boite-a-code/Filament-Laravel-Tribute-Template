# Changelog

All notable changes to `filament-laravel-tribute-template` will be documented in this file.

## [1.0.2] - 2026-09-12

Supersedes 1.0.1, which was tagged from an incomplete commit and carries the
new workflows and the screenshots without the fixes below.

### Fixed

- Primary buttons pinned their own background to `primary-500` with a white
  label, which bypassed Filament's contrast pairing and failed WCAG AA on all
  three palettes, down to 2.45:1 on Forge. The fill and the label are left to
  Filament again; the theme only adds the flat border and the lift, both
  derived from the fill. The six palette and mode combinations now measure
  between 4.66:1 and 5.51:1.
- Darkened the Forge `600` shade so the dark-mode button Filament picks from it
  clears 4.5:1 against its white label.

### Changed

- The release archive now excludes the press kit, the security policy and the
  build tooling, keeping only the sources, the config, the stylesheet and the
  published stub.
- Removed the three workflows superseded in 1.0.1.

### Added

- Six screenshots in the README, one per palette and colour mode.

## [1.0.1] - 2026-09-12

### Added

- `SECURITY.md` with a private reporting address.
- Dependabot covering Composer, npm and GitHub Actions, with a seven-day
  cooldown before a new release is proposed.

### Changed

- Workflows rebuilt in three jobs (tests, static analysis, code style), with
  every action pinned to a full commit SHA, read-only token permissions, job
  timeouts, `composer validate --strict` and `composer audit`. The matrix runs
  PHP 8.2 to 8.5 against Laravel 12 and 13, and a job compiles the stylesheet.

## [1.0.0] - 2026-09-12

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
