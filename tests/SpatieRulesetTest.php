<?php

namespace Permafrost\Tests\Unit;

use Permafrost\PhpCsFixerRules\Rulesets\RuleSet;
use Permafrost\PhpCsFixerRules\Rulesets\SpatieRuleset;
use PHPUnit\Framework\TestCase;

class SpatieRulesetTest extends TestCase
{
    /**
     * @test
     */
    public function itImplementsTheRulesetContract(): void
    {
        $ruleset = new SpatieRuleset();

        $this->assertInstanceOf(RuleSet::class, $ruleset);
    }

    /**
     * @test
     */
    public function itImplementsOnlyInterfaceMethods(): void
    {
        $reflect = new \ReflectionClass(new SpatieRuleset());

        $this->assertCount(3, $reflect->getMethods(\ReflectionMethod::IS_PUBLIC));
    }

    /**
     * @test
     */
    public function itReturnsAValidName(): void
    {
        $ruleset = new SpatieRuleset();

        $this->assertIsString($ruleset->name());
        $this->assertNotEmpty($ruleset->name());
        $this->assertEquals('spatie', strtolower($ruleset->name()));
    }

    /**
     * @test
     */
    public function itReturnsValidRules(): void
    {
        $ruleset = new SpatieRuleset();

        $this->assertIsArray($ruleset->rules());
        $this->assertNotEmpty($ruleset->rules());
        $this->assertGreaterThan(5, count($ruleset->rules()));
    }
}
