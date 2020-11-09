<?php

namespace Permafrost\Tests\Unit;

use Permafrost\PhpCsFixerRules\Finders\LaravelPackageFinder;
use PhpCsFixer\Finder;
use PHPUnit\Framework\TestCase;

class LaravelPackageFinderTest extends TestCase
{
    /**
     * @test
     */
    public function it_returns_a_php_cs_finder_object(): void
    {
        $finder = LaravelPackageFinder::create(__DIR__ . '/..');

        $this->assertInstanceOf(Finder::class, $finder);
    }
}
