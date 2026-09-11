<?php

declare(strict_types=1);

use Filament\Panel;
use Filament\Support\Colors\Color;
use Laboiteacode\LaravelDesign\Enums\Palette;
use Laboiteacode\LaravelDesign\LaravelDesignPlugin;

/**
 * @return array<int, int>
 */
function ldShades(): array
{
    return [50, 100, 200, 300, 400, 500, 600, 700, 800, 900, 950];
}

it('ships a complete OKLCH ramp for every palette', function (): void {
    foreach (Palette::cases() as $palette) {
        expect(array_keys($palette->shades()))->toBe(ldShades())
            ->and($palette->shades())->each->toStartWith('oklch(');
    }
});

it('registers the palette ramp as the panel primary color', function (): void {
    $panel = Panel::make();

    LaravelDesignPlugin::make()->palette(Palette::Forge)->register($panel);

    expect($panel->getColors()['primary'])->toBe(Palette::Forge->shades());
});

it('keeps danger on the Laravel red whatever the palette', function (): void {
    $panel = Panel::make();

    LaravelDesignPlugin::make()->palette(Palette::Cloud)->register($panel);

    expect($panel->getColors()['danger'])->toBe(Palette::Laravel->shades());
});

it('registers the warm neutral as the panel gray', function (): void {
    $panel = Panel::make();

    LaravelDesignPlugin::make()->register($panel);

    // The stylesheet paints its surfaces with `--gray-{shade}`; if Filament is
    // handed a different neutral, the panel renders two families of gray.
    expect(array_keys($panel->getColors()['gray']))->toBe(ldShades())
        ->and($panel->getColors()['gray'][950])->toBe('oklch(0.082 0.004 75)');
});

it('falls back to the configured palette when none is set explicitly', function (): void {
    config()->set('laravel-design.palette', 'cloud');

    $panel = Panel::make();
    LaravelDesignPlugin::make()->register($panel);

    expect($panel->getColors()['primary'])->toBe(Palette::Cloud->shades());
});

it('prefers an explicit palette over the configured one', function (): void {
    config()->set('laravel-design.palette', 'cloud');

    $panel = Panel::make();
    LaravelDesignPlugin::make()->palette(Palette::Forge)->register($panel);

    expect($panel->getColors()['primary'])->toBe(Palette::Forge->shades());
});

it('falls back to the Laravel palette when the configured value is unknown', function (): void {
    config()->set('laravel-design.palette', 'chartreuse');

    $panel = Panel::make();
    LaravelDesignPlugin::make()->register($panel);

    expect($panel->getColors()['primary'])->toBe(Palette::Laravel->shades());
});

it('hands the host app colors straight to the panel', function (): void {
    $panel = Panel::make();

    LaravelDesignPlugin::make()
        ->colors(['primary' => Color::Indigo])
        ->register($panel);

    expect($panel->getColors())->toBe(['primary' => Color::Indigo]);
});

it('leaves the panel colors untouched when asked to', function (): void {
    $panel = Panel::make();

    LaravelDesignPlugin::make()->withoutColors()->register($panel);

    expect($panel->getColors())->toBe([]);
});

it('applies the font and the max content width', function (): void {
    $panel = Panel::make();

    LaravelDesignPlugin::make()
        ->font('Inter')
        ->maxContentWidth()
        ->register($panel);

    expect($panel->getFontFamily())->toBe('Inter')
        ->and($panel->getMaxContentWidth())->toBe('full');
});

it('leaves the panel font and width alone when opted out', function (): void {
    $panel = Panel::make();

    LaravelDesignPlugin::make()->font(null)->register($panel);

    expect($panel->getFontFamily())->toBe('Inter Variable')
        ->and($panel->getMaxContentWidth())->toBeNull();
});

it('injects no runtime javascript', function (): void {
    // The theme is CSS-only: the palette travels through the panel's color
    // registration, never through a script that patches the DOM.
    $sources = array_merge(
        glob(__DIR__.'/../src/*.php') ?: [],
        glob(__DIR__.'/../src/*/*.php') ?: [],
    );

    expect($sources)->not->toBeEmpty();

    foreach ($sources as $source) {
        expect(file_get_contents($source))->not->toContain('<script');
    }
});
