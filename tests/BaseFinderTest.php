<?php

namespace Tests\Unit;

use Permafrost\PhpCsFixerRules\Finders\BaseFinder;
use PHPUnit\Framework\TestCase;

class BaseFinderTest extends TestCase
{
    /**
     * @test
     */
    public function it_only_includes_existing_paths(): void
    {
        $testDirs = [
            realpath(__DIR__ . '/../src'),
            realpath(__DIR__ . '/../tests'),
            realpath(__DIR__ . '/../missing-dir-1'),
            realpath(__DIR__ . '/../missing-dir-2'),
        ];
        $existingPaths = BaseFinder::onlyExistingPaths($testDirs);

        $this->assertCount(2, $existingPaths);
        $this->assertEquals([
            realpath(__DIR__ . '/../src'),
            realpath(__DIR__ . '/../tests'),
        ], $existingPaths);
    }
}
