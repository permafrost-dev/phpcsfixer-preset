<?php

namespace Permafrost\PhpCsFixerRules;

use Permafrost\PhpCsFixerRules\Rulesets\DefaultRuleset;
use Permafrost\PhpCsFixerRules\Rulesets\RuleSet;
use PhpCsFixer\Config;
use PhpCsFixer\Finder;

class SharedConfig
{
    public static function loadAndMergeRules(Ruleset $ruleset, array $rules): array
    {
        return array_merge($ruleset->rules(), $rules);
    }

    /**
     * @param \PhpCsFixer\Finder $finder
     * @param \Permafrost\PhpCsFixerRules\Rulesets\RuleSet|null $ruleset
     * @param array $rules
     * @param false $allowRiskyRules
     *
     * @return \PhpCsFixer\Config
     */
    public static function create(Finder $finder, ?Ruleset $ruleset = null, array $rules = [], $allowRiskyRules = false): Config
    {
        if ($ruleset === null) {
            $ruleset = new DefaultRuleset();
        }

        return Config::create()
            ->setFinder($finder)
            ->setRiskyAllowed($allowRiskyRules || $ruleset->allowRisky())
            ->setRules(static::loadAndMergeRules($ruleset, $rules));
    }
}
