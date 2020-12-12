<?php

namespace Permafrost\Tests\Unit;

use Permafrost\PhpCsFixerRules\Finders\BasicProjectFinder;
use PhpCsFixer\Finder;
use PHPUnit\Framework\TestCase;

class BasicProjectFinderTest extends TestCase
{
    /**
     * @test
     */
    public function itReturnsAPhpCsFinderObject(): void
    {
        $finder = BasicProjectFinder::create(__DIR__);

        $this->assertInstanceOf(Finder::class, $finder);
    }
}
