<?php

namespace Permafrost\Tests\Unit\Commands\Support;

use Permafrost\PhpCsFixerRules\Commands\Support\CustomConfigGenerator;
use PHPUnit\Framework\TestCase;

class CustomConfigGeneratorTest extends TestCase
{
    /** @test */
    public function it_sets_paths_correctly(): void
    {
        $generator = new CustomConfigGenerator();

        $generator->setPaths(['aaa'], ['bbb']);

        $this->assertSame(['aaa'], $generator->includePaths);
        $this->assertSame(['bbb'], $generator->excludePaths);
    }

    /** @test */
    public function it_calls_set_paths_from_the_constructor_using_provided_arguments(): void
    {
        $generator = new CustomConfigGenerator(['aaa'], ['bbb']);

        $this->assertSame(['aaa'], $generator->includePaths);
        $this->assertSame(['bbb'], $generator->excludePaths);
    }

    /** @test */
    public function it_generates_finder_code_with_included_paths_correctly(): void
    {
        $generator = new CustomConfigGenerator(['aaa', 'ccc'], ['bbb']);
        $code = $generator->generateFinderCode();

        $NL = PHP_EOL;
        $this->assertMatchesRegularExpression("~->in\(\[$NL\s*__DIR__ . '/aaa',$NL\s*__DIR__ . '/ccc',$NL\s*\]\)~s", $code);
        $this->assertStringContainsString("__DIR__ . '/aaa'," . PHP_EOL, $code);
        $this->assertStringContainsString("__DIR__ . '/ccc'," . PHP_EOL, $code);
    }

    /** @test */
    public function it_generates_finder_code_with_excluded_paths_correctly(): void
    {
        $generator = new CustomConfigGenerator(['aaa'], ['bbb']);
        $code = $generator->generateFinderCode();

        $this->assertStringContainsString("->notPath('bbb/*')" . PHP_EOL, $code);

        $generator = new CustomConfigGenerator(['aaa'], []);
        $code = $generator->generateFinderCode();

        $this->assertStringNotContainsString('->notPath(', $code);
    }

    /** @test */
    public function it_generates_finder_code_with_the_vendor_path_excluded(): void
    {
        $generator = new CustomConfigGenerator(['aaa'], ['bbb']);
        $code = $generator->generateFinderCode();

        $this->assertStringContainsString("->exclude(__DIR__ . '/vendor')", $code);
    }

    /** @test */
    public function it_generates_finder_code_that_includes_php_files(): void
    {
        $generator = new CustomConfigGenerator(['aaa'], ['bbb']);
        $code = $generator->generateFinderCode();

        $this->assertStringContainsString("->name('*.php')", $code);
    }

    /** @test */
    public function it_generates_finder_code_that_excludes_vcs_and_dotfiles(): void
    {
        $generator = new CustomConfigGenerator(['aaa'], ['bbb']);
        $code = $generator->generateFinderCode();

        $this->assertStringContainsString('->ignoreVCS(true)', $code);
        $this->assertStringContainsString('->ignoreDotFiles(true)', $code);
    }

    /** @test */
    public function it_generates_finder_code_that_creates_a_finder_class_instance(): void
    {
        $generator = new CustomConfigGenerator(['aaa'], ['bbb']);
        $code = $generator->generateFinderCode();

        $this->assertStringContainsString('Finder::create()', $code);
    }
}
