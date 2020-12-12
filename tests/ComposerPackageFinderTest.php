<?php

namespace Permafrost\Tests\Unit;

use Permafrost\PhpCsFixerRules\Finders\ComposerPackageFinder;
use PhpCsFixer\Finder;
use PHPUnit\Framework\TestCase;

class ComposerPackageFinderTest extends TestCase
{
    /**
     * @test
     */
    public function itReturnsAPhpCsFinderObject(): void
    {
        $finder = ComposerPackageFinder::create(__DIR__ . '/..');

        $this->assertInstanceOf(Finder::class, $finder);
    }
}
