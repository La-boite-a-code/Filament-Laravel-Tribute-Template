<?php

declare(strict_types=1);

namespace Laboiteacode\LaravelDesign\Commands;

use Illuminate\Console\Command;

class LaravelDesignCommand extends Command
{
    public $signature = 'laravel-design';

    public $description = 'LaravelDesign theme command.';

    public function handle(): int
    {
        $this->comment('LaravelDesign theme is installed and ready.');

        return self::SUCCESS;
    }
}
