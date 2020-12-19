<?php

namespace Permafrost\Tests\Unit;

use Permafrost\PhpCsFixerRules\SharedConfig;
use PHPUnit\Framework\TestCase;
use Permafrost\PhpCsFixerRules\Commands\Support\ConfigGenerator;

class ConfigGeneratorTest extends TestCase
{
    /** @test */
    public function it_imports_a_finder_classname_correctly(): void
    {
        $generator = new ConfigGenerator();
        $output = $generator->generate('Testing\\DummyNamespace\\TestFinder', 'TestRuleset');

        $this->assertStringContainsString('use Testing\\DummyNamespace\\TestFinder;', $output);
    }

    /** @test */
    public function it_creates_the_finder_class_correctly(): void
    {
        $generator = new ConfigGenerator();
        $output = $generator->generate('Testing\\DummyNamespace\\TestFinder', 'TestRuleset');

        $this->assertStringContainsString('TestFinder::create(', $output);
    }

    /** @test */
    public function it_imports_the_shared_config_class(): void
    {
        $generator = new ConfigGenerator();
        $output = $generator->generate('Testing\\DummyNamespace\\TestFinder', 'TestRuleset');

        $this->assertStringContainsString('use ' . trim(SharedConfig::class, '\\') . ';', $output);
    }

    /** @test */
    public function it_creates_an_instance_of_the_shared_config_class(): void
    {
        $generator = new ConfigGenerator();
        $output = $generator->generate('Testing\\DummyNamespace\\TestFinder', 'TestRuleset');

        $this->assertStringContainsString('SharedConfig::create(', $output);
    }


    /** @test */
    public function it_requires_the_composer_autoload_file(): void
    {
        $generator = new ConfigGenerator();
        $output = $generator->generate('Testing\\DummyNamespace\\TestFinder', 'TestRuleset');

        $this->assertEquals(1, preg_match("~^\s*require(_once)?\(__DIR__\s*\.\s*'/vendor/autoload.php'\);$~m", $output));
    }

    /** @test */
    public function it_imports_the_provided_ruleset_class(): void
    {
        $generator = new ConfigGenerator();
        $output = $generator->generate('Testing\\DummyNamespace\\TestFinder', 'TestRuleset');

        $this->assertStringContainsString('use Permafrost\\PhpCsFixerRules\\Rulesets\\TestRuleset;', $output);
    }

    /** @test */
    public function it_creates_an_instance_of_the_provided_ruleset_class(): void
    {
        $generator = new ConfigGenerator();
        $output = $generator->generate('Testing\\DummyNamespace\\TestFinder', 'TestRuleset');

        $this->assertStringContainsString('new TestRuleset(', $output);
    }
}
