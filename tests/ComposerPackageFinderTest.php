<?php

namespace Tests\Unit;

use Permafrost\PhpCsFixerRules\Finders\ComposerPackageFinder;
use PhpCsFixer\Finder;
use PHPUnit\Framework\TestCase;

class ComposerPackageFinderTest extends TestCase
{
    /**
     * @test
     */
    public function it_returns_a_php_cs_finder_object(): void
    {
        $finder = ComposerPackageFinder::create(__DIR__ . '/..');

        $this->assertInstanceOf(Finder::class, $finder);
    }
}
