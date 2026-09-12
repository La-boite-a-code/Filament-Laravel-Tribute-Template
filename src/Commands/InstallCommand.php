<?php

declare(strict_types=1);

namespace LaBoiteACode\LaravelTributeTemplate\Commands;

use Spatie\LaravelPackageTools\Commands\InstallCommand as BaseInstallCommand;
use Spatie\LaravelPackageTools\Package;

/**
 * Branded install command.
 *
 * Spatie's base command derives its name from the package "short name"
 * (`Str::after($name, 'laravel-')`), which would turn this package's name
 * into a generic `tribute-template:install`. This subclass keeps every published step of the
 * base command while restoring the branded `laravel-tribute-template:install` name and
 * making it visible in `php artisan list`.
 */
class InstallCommand extends BaseInstallCommand
{
    public function __construct(Package $package)
    {
        parent::__construct($package);

        $this->setName('laravel-tribute-template:install');
        $this->setHidden(false);
    }

    /**
     * Mirror spatie's install pipeline but end with a branded completion
     * message. The base command prints the package "short name"
     * (`design has been installed!`), which leaks the stripped name.
     */
    public function handle(): void
    {
        $this
            ->processStartWith()
            ->processPublishes()
            ->processAskToRunMigrations()
            ->processCopyServiceProviderInApp()
            ->processStarRepo()
            ->processEndWith();

        $this->info('Filament Laravel Tribute Template has been installed!');
    }
}
