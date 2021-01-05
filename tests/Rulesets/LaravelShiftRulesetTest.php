<?php

namespace Permafrost\Tests\Unit\Rulesets;

use Permafrost\PhpCsFixerRules\Rulesets\LaravelShiftRuleset;
use Permafrost\PhpCsFixerRules\Rulesets\RuleSet;
use PHPUnit\Framework\TestCase;

class LaravelShiftRulesetTest extends TestCase
{
    /**
     * @test
     */
    public function it_implements_the_ruleset_contract(): void
    {
        $ruleset = new LaravelShiftRuleset();

        $this->assertInstanceOf(RuleSet::class, $ruleset);
    }

    /**
     * @test
     */
    public function it_implements_only_interface_methods(): void
    {
        $reflect = new \ReflectionClass(new LaravelShiftRuleset());

        $this->assertCount(3, $reflect->getMethods(\ReflectionMethod::IS_PUBLIC));
    }

    /**
     * @test
     */
    public function it_returns_a_valid_name(): void
    {
        $ruleset = new LaravelShiftRuleset();

        $this->assertIsString($ruleset::name());
        $this->assertNotEmpty($ruleset::name());
        $this->assertEquals('laravel_shift', strtolower($ruleset::name()));
    }

    /**
     * @test
     */
    public function it_returns_valid_rules(): void
    {
        $ruleset = new LaravelShiftRuleset();

        $this->assertIsArray($ruleset->rules());
        $this->assertNotEmpty($ruleset->rules());
        $this->assertGreaterThan(5, count($ruleset->rules()));
    }

    /** @test */
    public function it_returns_a_bool_from_allowRisky_method(): void
    {
        $ruleset = new LaravelShiftRuleset();

        $this->assertIsBool($ruleset->allowRisky());
    }
}
