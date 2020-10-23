<?php

namespace Permafrost\PhpCsFixerRules\Finders;

use PhpCsFixer\Finder;

abstract class BaseFinder
{

    public static function onlyExistingPaths(array $paths): array
    {
        return array_filter($paths, function($path) {
            return file_exists($path) && is_dir($path);
        });
    }
    
    abstract public static function create(string $baseDir);
}
