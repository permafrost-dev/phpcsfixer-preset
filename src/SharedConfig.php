<?php

namespace Permafrost\PhpCsFixerRules;

use Permafrost\PhpCsFixerRules\Rulesets\DefaultRuleset;
use Permafrost\PhpCsFixerRules\Rulesets\RuleSet;
use PhpCsFixer\Config;
use PhpCsFixer\Finder;

class SharedConfig
{
    /**
     * @param Finder $finder
     * @param RuleSet|null $ruleset
     *
     * @return Config
     */
    public static function create(Finder $finder, ?Ruleset $ruleset = null): Config
    {
        if ($ruleset === null) {
            $ruleset = new DefaultRuleset();
        }

        return (new Config())
            ->setFinder($finder)
            ->setRiskyAllowed($ruleset->allowRisky())
            ->setRules($ruleset->rules());
    }
}
