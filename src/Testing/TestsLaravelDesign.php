<?php

declare(strict_types=1);

namespace Laboiteacode\LaravelDesign\Testing;

use Livewire\Features\SupportTesting\Testable;

/**
 * @mixin Testable
 */
class TestsLaravelDesign
{
    public function exampleTestableMethod(): \Closure
    {
        return function (): Testable {
            /** @var Testable $this */
            return $this;
        };
    }
}
