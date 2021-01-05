<?php

namespace Permafrost\Tests\Unit\Support;

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

    /** @test */
    public function it_detects_when_a_string_starts_with_another_string(): void
    {
        $this->assertTrue(Str::startsWith('test string', 'test'));
        $this->assertTrue(Str::startsWith('Test string', 'Test'));
        $this->assertFalse(Str::startsWith('test string', 'Test'));
        $this->assertFalse(Str::startsWith('test string', ''));
        $this->assertFalse(Str::startsWith('', ''));
        $this->assertFalse(Str::startsWith('', 'test'));
        $this->assertFalse(Str::startsWith('test string', ' '));
    }

    /** @test */
    public function it_returns_the_string_after_the_last_instance_of_a_substring(): void
    {
        $this->assertEquals('tString', Str::afterLast('testString', 's'));
        $this->assertEquals('estString', Str::afterLast('TestString', 'T'));
        $this->assertEquals('TestString', Str::afterLast('TestString', 'X'));
    }
}
