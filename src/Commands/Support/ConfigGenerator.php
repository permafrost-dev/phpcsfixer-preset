<?php

namespace Permafrost\PhpCsFixerRules\Commands\Support;

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

        $code = <<<CODE
<?php
require_once(__DIR__.'/vendor/autoload.php');

use $finderName;
use Permafrost\\PhpCsFixerRules\\Rulesets\\$rulesetClass;
use Permafrost\\PhpCsFixerRules\\SharedConfig;

// optional: chain additiional custom Finder options:
\$finder = $finderNameShort::create(__DIR__);

return SharedConfig::create(\$finder, new $rulesetClass());
CODE;

        return trim($code);
    }
}
