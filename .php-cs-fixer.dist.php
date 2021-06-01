<?php
require_once(__DIR__.'/vendor/autoload.php');

use Permafrost\PhpCsFixerRules\Finders\ComposerPackageFinder;
use Permafrost\PhpCsFixerRules\Rulesets\DefaultRuleset;
use Permafrost\PhpCsFixerRules\SharedConfig;

// optional: chain additiional custom Finder options:
$finder = ComposerPackageFinder::create(__DIR__);

return SharedConfig::create($finder, new DefaultRuleset());