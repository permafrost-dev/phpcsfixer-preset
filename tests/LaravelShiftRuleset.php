<?php

namespace Permafrost\Tests\Unit;

use Permafrost\PhpCsFixerRules\Rulesets\LaravelShiftRuleset;
use Permafrost\PhpCsFixerRules\Rulesets\RuleSet;
use PHPUnit\Framework\TestCase;

class LaravelShiftRulesetTest extends TestCase
{
    /**
     * @test
     */
    public function itImplementsTheRulesetContract(): void
    {
        $ruleset = new LaravelShiftRuleset();

        $this->assertInstanceOf(RuleSet::class, $ruleset);
    }

    /**
     * @test
     */
    public function itImplementsOnlyInterfaceMethods(): void
    {
        $reflect = new \ReflectionClass(new LaravelShiftRuleset());

        $this->assertCount(3, $reflect->getMethods(\ReflectionMethod::IS_PUBLIC));
    }

    /**
     * @test
     */
    public function itReturnsAValidName(): void
    {
        $ruleset = new LaravelShiftRuleset();

        $this->assertIsString($ruleset->name());
        $this->assertNotEmpty($ruleset->name());
        $this->assertEquals('laravel_shift', strtolower($ruleset->name()));
    }

    /**
     * @test
     */
    public function itReturnsValidRules(): void
    {
        $ruleset = new LaravelShiftRuleset();

        $this->assertIsArray($ruleset->rules());
        $this->assertNotEmpty($ruleset->rules());
        $this->assertGreaterThan(5, count($ruleset->rules()));
    }
}
