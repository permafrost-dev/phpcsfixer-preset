<?php

namespace Permafrost\Tests\Unit;

use Permafrost\PhpCsFixerRules\Support\Str;
use PHPUnit\Framework\TestCase;

class StrHelperTest extends TestCase
{
    /** @test */
    public function it_converts_words_to_snake_case(): void
    {
        $this->assertEquals('test_string', Str::snake('TestString'));
        $this->assertEquals('test_string', Str::snake('testString'));
        $this->assertEquals('test_string', Str::snake('test string'));
        $this->assertEquals('test_string', Str::snake('test-string'));
        $this->assertEquals('test_string', Str::snake('Test_String'));
    }

    /** @test */
    public function it_converts_words_to_studly_case(): void
    {
        $this->assertEquals('TestString', Str::studly('TestString'));
        $this->assertEquals('TestString', Str::studly('testString'));
        $this->assertEquals('TestString', Str::studly('test string'));
        $this->assertEquals('TestString', Str::studly('test-string'));
        $this->assertEquals('TestString', Str::studly('test_string'));
    }
}