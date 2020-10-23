<?php

namespace Tests\Unit;

use Permafrost\PhpCsFixerRules\SharedConfig;
use Permafrost\PhpCsFixerRules\Finders\BasicProjectFinder;
use PhpCsFixer\Config;
use PhpCsFixer\Finder;
use PHPUnit\Framework\TestCase;

class BasicProjectFinderTest extends TestCase
{
    /**
     * @test
     */
    public function it_returns_a_php_cs_finder_object(): void
    {
        $finder = BasicProjectFinder::create(__DIR__);

        $this->assertInstanceOf(Finder::class, $finder);
    }
}
