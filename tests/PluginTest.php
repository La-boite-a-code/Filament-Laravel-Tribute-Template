<?php

declare(strict_types=1);

use Filament\Panel;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\Width;
use Filament\Support\View\Components\ButtonComponent;
use LaBoiteACode\LaravelTributeTemplate\Enums\Palette;
use LaBoiteACode\LaravelTributeTemplate\LaravelTributeTemplatePlugin;

function makePanel(): Panel
{
    return Panel::make()->id('test');
}

it('registers the Laravel palette and font by default', function (): void {
    $panel = makePanel();

    LaravelTributeTemplatePlugin::make()->register($panel);

    $colors = $panel->getColors();

    expect($colors)->toHaveKeys(['primary', 'danger', 'gray', 'info', 'success', 'warning'])
        ->and($colors['primary'])->toBe(Palette::Laravel->shades())
        ->and($colors['gray'])->toBe(Color::Stone)
        ->and($panel->getFontFamily())->toBe('Instrument Sans');
});

it('switches palette from config or from the plugin', function (): void {
    config()->set('filament-laravel-tribute-template.palette', 'forge');

    $fromConfig = makePanel();
    LaravelTributeTemplatePlugin::make()->register($fromConfig);

    $fromPlugin = makePanel();
    LaravelTributeTemplatePlugin::make()->palette(Palette::Cloud)->register($fromPlugin);

    expect($fromConfig->getColors())->toMatchArray(['primary' => Palette::Forge->shades(), 'gray' => Color::Neutral])
        ->and($fromPlugin->getColors())->toMatchArray(['primary' => Palette::Cloud->shades(), 'gray' => Color::Slate]);
});

it('rejects unknown palettes', function (): void {
    LaravelTributeTemplatePlugin::make()->palette('vapor')->resolvePalette();
})->throws(InvalidArgumentException::class, 'vapor');

it('keeps each brand look through Filament button colour maps', function (Palette $palette, int $background, int $text): void {
    $map = app(ButtonComponent::class)->getColorMap($palette->shades());

    expect($palette->shades()[$palette->brandShade()])->toBe(Color::convertToOklch($palette->hex()))
        ->and($map['bg'])->toBe($background)
        ->and($map['text'])->toBe($text);
})->with([
    'laravel: white text on red' => [Palette::Laravel, 600, 0],
    'forge: dark text on bright green' => [Palette::Forge, 400, 800],
    'cloud: white text on blue' => [Palette::Cloud, 600, 0],
]);

it('reads font, sidebar width and overrides from config', function (): void {
    config()->set('filament-laravel-tribute-template.colors', ['gray' => '#808080']);
    config()->set('filament-laravel-tribute-template.font', 'Albert Sans');
    config()->set('filament-laravel-tribute-template.sidebar_width', '18rem');

    $panel = makePanel();

    LaravelTributeTemplatePlugin::make()->register($panel);

    expect($panel->getColors())->toMatchArray(['gray' => '#808080'])
        ->and($panel->getFontFamily())->toBe('Albert Sans')
        ->and($panel->getSidebarWidth())->toBe('18rem');
});

it('passes hex, rgb() and oklch() strings through to Filament untouched', function (): void {
    $panel = makePanel();

    LaravelTributeTemplatePlugin::make()
        ->colors([
            'primary' => '#FF2D20',
            'gray' => 'rgb(120, 113, 108)',
            'info' => 'oklch(0.6 0.15 240)',
        ])
        ->register($panel);

    expect($panel->getColors())->toMatchArray([
        'primary' => '#FF2D20',
        'gray' => 'rgb(120, 113, 108)',
        'info' => 'oklch(0.6 0.15 240)',
    ]);
});

it('resolves palette closures lazily, when the panel reads its colours', function (): void {
    $called = false;
    $panel = makePanel();

    LaravelTributeTemplatePlugin::make()
        ->colors(['primary' => function () use (&$called): string {
            $called = true;

            return 'indigo';
        }])
        ->register($panel);

    expect($called)->toBeFalse();

    $panel->getColors();

    expect($called)->toBeTrue();
});

it('keeps the panel palette, font and width when opted out', function (): void {
    $panel = makePanel()
        ->colors(['primary' => Color::Blue])
        ->font('Albert Sans')
        ->sidebarWidth('30rem');

    LaravelTributeTemplatePlugin::make()
        ->withoutColors()
        ->font(null)
        ->sidebarWidth(null)
        ->register($panel);

    expect($panel->getColors())->toBe(['primary' => Color::Blue])
        ->and($panel->getFontFamily())->toBe('Albert Sans')
        ->and($panel->getSidebarWidth())->toBe('30rem');
});

it('applies a full or preset max content width', function (): void {
    $default = makePanel();
    LaravelTributeTemplatePlugin::make()->register($default);

    $full = makePanel();
    LaravelTributeTemplatePlugin::make()->maxContentWidth()->register($full);

    $preset = makePanel();
    LaravelTributeTemplatePlugin::make()->maxContentWidth('5xl')->register($preset);

    expect($default->getMaxContentWidth())->toBeNull()
        ->and($full->getMaxContentWidth())->toBe(Width::Full)
        ->and($preset->getMaxContentWidth())->toBe('5xl');
});
