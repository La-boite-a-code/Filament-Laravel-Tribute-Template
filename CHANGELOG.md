# Changelog

All notable changes to `laravel-design` will be documented in this file.

## [Unreleased]

### Added
- Initial package skeleton based on `filament/plugin-skeleton`.
- `LaravelDesignPlugin` for Filament v5 panel registration.
- `LaravelDesignServiceProvider` (Spatie package-tools) for asset, config,
  views, lang and migration loading.
- Theme entrypoint at `resources/css/index.css` + Vite build pipeline.
- Pest test suite (Orchestra Testbench), PHPStan baseline, Pint config.
- GitHub Actions workflows: tests, PHPStan, Pint auto-fix.
