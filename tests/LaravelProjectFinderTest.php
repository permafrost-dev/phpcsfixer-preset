<?php

namespace Permafrost\Tests\Unit;

use Permafrost\PhpCsFixerRules\Finders\LaravelProjectFinder;
use PhpCsFixer\Finder;
use PHPUnit\Framework\TestCase;

class LaravelProjectFinderTest extends TestCase
{
    /**
     * @test
     */
    public function itReturnsAPhpCsFinderObject(): void
    {
        $finder = LaravelProjectFinder::create(__DIR__ . '/..');

        $this->assertInstanceOf(Finder::class, $finder);
    }
}
