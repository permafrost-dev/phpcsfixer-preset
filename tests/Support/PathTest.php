<?php

namespace Permafrost\Tests\Unit\Support;

use Permafrost\PhpCsFixerRules\Support\Path;
use Permafrost\PhpCsFixerRules\Support\Str;
use PHPUnit\Framework\TestCase;

class PathTest extends TestCase
{
    /** @test */
    public function it_returns_an_empty_collection_for_invalid_paths(): void
    {
        $names = Path::getSubDirectoryNames(__DIR__ . '/invalid-dir-name');

        $this->assertEmpty($names->toArray());
    }

    /** @test */
    public function it_gets_sub_directory_names(): void
    {
        $names = Path::getSubDirectoryNames(__DIR__ . '/..');

        $actual = array_filter(scandir(__DIR__ . '/..'), function ($item) {
            return $item !== Str::afterLast(__DIR__, DIRECTORY_SEPARATOR)
                && $item !== '.'
                && $item !== '..'
                && is_dir($item);
        });

        $this->assertSame($actual, $names->toArray());
    }

    /** @test */
    public function it_gets_sub_directory_names_except_excluded(): void
    {
        $names = Path::getSubDirectoryNames(__DIR__ . '/..', [Str::afterLast(__DIR__, DIRECTORY_SEPARATOR)]);

        $actual = array_filter(scandir(__DIR__ . '/..'), function ($item) {
            return $item !== Str::afterLast(__DIR__, DIRECTORY_SEPARATOR)
                && $item !== '.'
                && $item !== '..'
                && is_dir($item);
        });

        $this->assertSame($actual, $names->toArray());
    }

    /** @test */
    public function it_does_not_return_dot_or_dotdot_in_sub_directory_names(): void
    {
        $names = Path::getSubDirectoryNames(__DIR__);

        $this->assertFalse(in_array('.', $names->toArray()));
        $this->assertFalse(in_array('..', $names->toArray()));
    }
}
