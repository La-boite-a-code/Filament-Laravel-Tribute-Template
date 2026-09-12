<?php

declare(strict_types=1);

/**
 * Guards the contract between the theme stylesheet and Filament: the theme
 * must only hook onto class names Filament actually renders, and must read
 * colours from Filament's own palette variables rather than a private copy.
 */
$css = file_get_contents(__DIR__.'/../resources/css/index.css');

it('reads colours from the panel palette, not a private scale', function () use ($css): void {
    expect($css)->not->toContain('--fi-color-');
});

it('only targets class hooks that exist in Filament', function () use ($css): void {
    preg_match_all('/\.(fi-[a-z0-9-]+)/', $css, $matches);

    $classes = array_unique($matches[1]);
    $haystack = '';

    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator(__DIR__.'/../vendor/filament', FilesystemIterator::SKIP_DOTS),
    );

    /** @var SplFileInfo $file */
    foreach ($iterator as $file) {
        if (! preg_match('#/(resources|src)/#', $file->getPathname())) {
            continue;
        }

        if (! in_array($file->getExtension(), ['php', 'css'], true)) {
            continue;
        }

        $haystack .= (string) file_get_contents($file->getPathname());
    }

    $missing = array_values(array_filter(
        $classes,
        fn (string $class): bool => ! str_contains($haystack, $class),
    ));

    expect($classes)->not->toBeEmpty()
        ->and($missing)->toBe([]);
});
