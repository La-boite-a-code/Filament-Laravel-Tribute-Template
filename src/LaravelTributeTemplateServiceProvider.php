<?php

declare(strict_types=1);

namespace LaBoiteACode\LaravelTributeTemplate;

use Filament\Support\Assets\Asset;
use Filament\Support\Facades\FilamentAsset;
use Filament\Support\Facades\FilamentIcon;
use LaBoiteACode\LaravelTributeTemplate\Commands\InstallCommand;
use LaBoiteACode\LaravelTributeTemplate\Testing\TestsLaravelTributeTemplate;
use Livewire\Features\SupportTesting\Testable;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class LaravelTributeTemplateServiceProvider extends PackageServiceProvider
{
    public static string $name = 'filament-laravel-tribute-template';

    public static string $viewNamespace = 'filament-laravel-tribute-template';

    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package->name(static::$name)
            ->hasConfigFile(static::$name);

        // Register the branded `laravel-tribute-template:install` command. Built
        // directly (instead of `->hasInstallCommand()`) so it uses our subclass
        // rather than the name spatie derives from the stripped short name.
        $installCommand = new InstallCommand($package);
        $installCommand
            ->publishConfigFile()
            ->endWith(function (InstallCommand $command): void {
                $command->call('vendor:publish', [
                    '--tag' => 'filament-laravel-tribute-template-theme',
                ]);
            })
            ->askToStarRepoOnGitHub('La-boite-a-code/Filament-Laravel-Tribute-Template');

        $package->consoleCommands[] = $installCommand;

        if (file_exists($package->basePath('/../resources/views'))) {
            $package->hasViews(static::$viewNamespace);
        }
    }

    public function packageRegistered(): void {}

    public function packageBooted(): void
    {
        // Asset Registration
        FilamentAsset::register(
            $this->getAssets(),
            $this->getAssetPackageName()
        );

        FilamentAsset::registerScriptData(
            $this->getScriptData(),
            $this->getAssetPackageName()
        );

        FilamentIcon::register($this->getIcons());

        // Filament panel theme entry-point — published to the host app
        // by the install command (or via `vendor:publish --tag=filament-laravel-tribute-template-theme`).
        if (app()->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../stubs/theme.css' => resource_path('css/filament/admin/theme.css'),
            ], 'filament-laravel-tribute-template-theme');
        }

        // Testing
        Testable::mixin(new TestsLaravelTributeTemplate);
    }

    protected function getAssetPackageName(): ?string
    {
        return 'laboiteacode/filament-laravel-tribute-template';
    }

    /**
     * @return array<Asset>
     *
     * The theme CSS is compiled by the host application's Vite/Tailwind
     * pipeline: the published `theme.css` imports this package's
     * `resources/css/index.css`. No precompiled CSS is shipped here.
     *
     * If you need to ship runtime JS / Alpine components later, register
     * them in this method.
     */
    protected function getAssets(): array
    {
        return [];
    }

    /**
     * @return array<string>
     */
    protected function getIcons(): array
    {
        return [];
    }

    /**
     * @return array<string, mixed>
     */
    protected function getScriptData(): array
    {
        return [];
    }
}
