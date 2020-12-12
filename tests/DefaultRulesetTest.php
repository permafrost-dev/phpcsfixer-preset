<?php

namespace Permafrost\Tests\Unit;

use Permafrost\PhpCsFixerRules\Rulesets\DefaultRuleset;
use Permafrost\PhpCsFixerRules\Rulesets\RuleSet;
use PHPUnit\Framework\TestCase;

class DefaultRulesetTest extends TestCase
{
    /**
     * @test
     */
    public function itImplementsTheRulesetContract(): void
    {
        $ruleset = new DefaultRuleset();

        $this->assertInstanceOf(RuleSet::class, $ruleset);
    }

    /**
     * @test
     */
    public function itImplementsOnlyInterfaceMethods(): void
    {
        $reflect = new \ReflectionClass(new DefaultRuleset());
        $this->assertCount(3, $reflect->getMethods(\ReflectionMethod::IS_PUBLIC));
    }

    /**
     * @test
     */
    public function itReturnsAValidName(): void
    {
        $ruleset = new DefaultRuleset();

        $this->assertIsString($ruleset->name());
        $this->assertNotEmpty($ruleset->name());
        $this->assertEquals('default', strtolower($ruleset->name()));
    }

    /**
     * @test
     */
    public function itReturnsValidRules(): void
    {
        $ruleset = new DefaultRuleset();

        $this->assertIsArray($ruleset->rules());
        $this->assertNotEmpty($ruleset->rules());
    }
}
