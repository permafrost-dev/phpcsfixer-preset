<?php

namespace Permafrost\Tests\Unit\Rulesets;

use Permafrost\PhpCsFixerRules\Rulesets\DefaultRuleset;
use Permafrost\Tests\Unit\RulesetTestCase;

class DefaultRulesetTest extends RulesetTestCase
{
    public function getRulesetClass(): string
    {
        return DefaultRuleset::class;
    }
}
