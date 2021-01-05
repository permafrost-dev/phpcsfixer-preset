<?php

namespace Permafrost\PhpCsFixerRules\Commands\Support;

use PhpCsFixer\Finder;

class ConfigGenerator
{
    /**
     * Generates a php-cs-fixer config file.
     *
     * @param string $finderName
     * @param string $rulesetClass
     *
     * @return string
     */
    public function generate(string $finderName, string $rulesetClass): string
    {
        //remove the namespace from the finder classname
        $finderNameParts = explode('\\', $finderName);
        $finderNameShort = array_pop($finderNameParts);

        $finderCode = trim($this->generateFinderCode(), "; \t\n\r\0\x0B");
        $finderCode = str_replace('{finderNameShort}', $finderNameShort, $finderCode);

        $code = <<<CODE
            <?php
            require_once(__DIR__ . '/vendor/autoload.php');

            use $finderName;
            use Permafrost\\PhpCsFixerRules\\Rulesets\\$rulesetClass;
            use Permafrost\\PhpCsFixerRules\\SharedConfig;

            // optional: chain additional custom Finder options:
            \$finder = {$finderCode};

            return SharedConfig::create(\$finder, new $rulesetClass());
            CODE;

        return trim($code);
    }

    public function generateFinderCode(): string
    {
        return '{finderNameShort}::create(__DIR__)';
    }
}
