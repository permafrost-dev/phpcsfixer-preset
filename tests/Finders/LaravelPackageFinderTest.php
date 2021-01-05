<?php

namespace Permafrost\Tests\Unit\Finders;

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
        $finder = LaravelPackageFinder::create(__DIR__ . '/../..');

        $this->assertInstanceOf(Finder::class, $finder);
    }

    /** @test */
    public function it_returns_config_type_names_containing_the_word_laravel(): void
    {
        foreach (LaravelPackageFinder::configTypes() as $type) {
            $this->assertStringContainsString('laravel', $type);
        }
    }
}
