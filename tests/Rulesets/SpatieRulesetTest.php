<?php

namespace Permafrost\Tests\Unit\Rulesets;

use Permafrost\PhpCsFixerRules\Rulesets\SpatieRuleset;
use Permafrost\Tests\Unit\RulesetTestCase;

class SpatieRulesetTest extends RulesetTestCase
{
    public function getRulesetClass(): string
    {
        return SpatieRuleset::class;
    }
}
