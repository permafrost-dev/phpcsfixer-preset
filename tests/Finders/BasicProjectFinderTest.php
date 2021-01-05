<?php

namespace Permafrost\Tests\Unit\Finders;

use Permafrost\PhpCsFixerRules\Finders\BasicProjectFinder;
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

    /** @test */
    public function it_returns_project_as_one_of_the_config_types(): void
    {
        $this->assertContains('project', BasicProjectFinder::configTypes());
    }
}
