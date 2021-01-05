<?php

namespace Permafrost\Tests\Unit\Rulesets;

use Permafrost\PhpCsFixerRules\Rulesets\PhpUnitRuleset;
use Permafrost\Tests\Unit\RulesetTestCase;

class PhpUnitRulesetTest extends RulesetTestCase
{
    public function getRulesetClass(): string
    {
        return PhpUnitRuleset::class;
    }
}
