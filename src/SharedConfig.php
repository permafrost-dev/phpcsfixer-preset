<?php

namespace Permafrost\PhpCsFixerRules;

use PhpCsFixer\Config;
use PhpCsFixer\Finder;

class SharedConfig
{
    public static function loadAndMergeRules(array $rules): array
    {
        return array_merge(require __DIR__ . '/rules.php', $rules);
    }

    /**
     * @param \PhpCsFixer\Finder $finder
     * @param array $rules
     * @param false $allowRiskyRules
     *
     * @return \PhpCsFixer\Config
     */
    public static function create(Finder $finder, array $rules = [], $allowRiskyRules = false): Config
    {
        return Config::create()
            ->setFinder($finder)
            ->setRiskyAllowed($allowRiskyRules)
            ->setRules(static::loadAndMergeRules($rules));
    }
}
