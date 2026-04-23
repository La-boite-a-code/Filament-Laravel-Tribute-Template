# LaravelDesign

A premium Filament v5 theme by **La Boite à Code**.

> ⚠️ This package is **proprietary**. See [LICENSE.md](./LICENSE.md). A valid
> license is required for production use.

## Requirements

- PHP `^8.2`
- Laravel `^12.0`
- Filament `^5.0`

## Installation

```bash
composer require laboiteacode/laravel-design
```

> During development, the package is loaded as a path repository from
> `packages/laravel-design`. See the root `composer.json` of this app.

Run the install command to publish config and assets:

```bash
php artisan laravel-design:install
```

## Usage

Register the plugin on your Filament panel provider:

```php
use Laboiteacode\LaravelDesign\LaravelDesignPlugin;

public function panel(Panel $panel): Panel
{
    return $panel
        ->plugin(LaravelDesignPlugin::make());
}
```

## Building the theme assets

From `packages/laravel-design`:

```bash
npm install
npm run build
```

The compiled CSS is emitted to `resources/dist/laravel-design.css` and
loaded automatically by `LaravelDesignServiceProvider`.

## Testing

```bash
composer test
```

## Configuration

Publish the config file to override defaults:

```bash
php artisan vendor:publish --tag="laravel-design-config"
```

## Roadmap

- [ ] Licensing layer (Anystack)
- [ ] Light & dark variants
- [ ] Pre-built component skins (tables, forms, dashboards)
- [ ] Documentation site

## Credits

- Built on top of [`filamentphp/plugin-skeleton`](https://github.com/filamentphp/plugin-skeleton).
- Maintained by [La Boite à Code](https://laboiteacode.fr).

## License

Proprietary — see [LICENSE.md](./LICENSE.md).
