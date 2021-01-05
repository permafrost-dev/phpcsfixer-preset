<?php

namespace Permafrost\Tests\Unit;

use Permafrost\PhpCsFixerRules\Rulesets\RuleSet;
use Permafrost\PhpCsFixerRules\Support\Str;
use PHPUnit\Framework\TestCase;

/**
 * Base `TestCase` class for `Ruleset` unit tests, because all tests are identical except for
 * the `Ruleset` class being tested.
 */
abstract class RulesetTestCase extends TestCase
{
    abstract public function getRulesetClass(): string;

    public function getRuleset(array $args = [])
    {
        $class = $this->getRulesetClass();

        return new $class($args);
    }

    /** @test */
    public function it_implements_the_ruleset_contract(): void
    {
        $ruleset = $this->getRuleset();

        $this->assertInstanceOf(RuleSet::class, $ruleset);
    }

    /** @test */
    public function it_implements_only_interface_methods(): void
    {
        $reflect = new \ReflectionClass($this->getRuleset());
        $this->assertCount(4, $reflect->getMethods(\ReflectionMethod::IS_PUBLIC));
    }

    /** @test */
    public function it_returns_a_valid_name(): void
    {
        $ruleset = $this->getRuleset();

        $expectedName = Str::afterLast($ruleset::name(), '\\');
        $expectedName = Str::snake(str_replace('Ruleset', '', $expectedName));

        $this->assertIsString($ruleset::name());
        $this->assertNotEmpty($ruleset::name());
        $this->assertEquals($expectedName, strtolower($ruleset::name()));
    }

    /** @test */
    public function it_returns_valid_rules(): void
    {
        $ruleset = $this->getRuleset();

        $this->assertIsArray($ruleset->rules());
        $this->assertNotEmpty($ruleset->rules());
    }

    /** @test */
    public function it_returns_a_bool_from_allow_risky_method(): void
    {
        $ruleset = $this->getRuleset();

        $this->assertIsBool($ruleset->allowRisky());
    }

    /** @test */
    public function it_merges_additional_rules(): void
    {
        $rulesetBase = $this->getRuleset();
        $baseRules = $rulesetBase->rules();

        $ruleset = $this->getRuleset(['__MERGED_RULE__' => 12]);
        $rules = $ruleset->rules();

        $this->assertIsArray($rules);
        $this->assertCount(count($baseRules) + 1, $rules);
        $this->assertArrayHasKey('__MERGED_RULE__', $rules);
        $this->assertEquals(12, $rules['__MERGED_RULE__']);
    }
}
