<?php

namespace Permafrost\Tests\Unit;

use Permafrost\PhpCsFixerRules\Commands\Support\FinderMap;
use Permafrost\PhpCsFixerRules\Finders\BasicProjectFinder;
use Permafrost\PhpCsFixerRules\Finders\ComposerPackageFinder;
use Permafrost\PhpCsFixerRules\Finders\LaravelPackageFinder;
use PHPUnit\Framework\TestCase;

class FinderMapTest extends TestCase
{
    /** @test */
    public function it_maps_config_types_to_classnames(): void
    {
        $map = new FinderMap([ComposerPackageFinder::class]);

        foreach (ComposerPackageFinder::configTypes() as $type) {
            $this->assertArrayHasKey($type, $map->getMap());
        }

        $expectedMap = [];

        foreach (ComposerPackageFinder::configTypes() as $type) {
            $expectedMap[$type] = ComposerPackageFinder::class;
        }

        $this->assertEquals($expectedMap, $map->getMap());
    }

    /** @test */
    public function it_finds_config_types_and_returns_the_correct_classname(): void
    {
        $map = new FinderMap([ComposerPackageFinder::class, LaravelPackageFinder::class]);

        foreach (ComposerPackageFinder::configTypes() as $type) {
            $this->assertEquals(ComposerPackageFinder::class, $map->find($type));
        }

        foreach (LaravelPackageFinder::configTypes() as $type) {
            $this->assertEquals(LaravelPackageFinder::class, $map->find($type));
        }
    }

    /** @test */
    public function it_returns_the_basic_project_finder_classname_when_find_fails(): void
    {
        $map = new FinderMap([ComposerPackageFinder::class]);

        $this->assertEquals(BasicProjectFinder::class, $map->find('test'));
    }
}
