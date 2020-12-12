<?php

namespace Permafrost\Tests\Unit;

use Permafrost\PhpCsFixerRules\Finders\BaseFinder;
use PHPUnit\Framework\TestCase;

class BaseFinderTest extends TestCase
{
    /**
     * @test
     */
    public function itOnlyIncludesExistingPaths(): void
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
