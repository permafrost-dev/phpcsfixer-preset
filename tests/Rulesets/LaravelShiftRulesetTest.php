<?php

namespace Permafrost\Tests\Unit\Rulesets;

use Permafrost\PhpCsFixerRules\Rulesets\LaravelShiftRuleset;
use Permafrost\Tests\Unit\RulesetTestCase;

class LaravelShiftRulesetTest extends RulesetTestCase
{
    public function getRulesetClass(): string
    {
        return LaravelShiftRuleset::class;
    }
}
