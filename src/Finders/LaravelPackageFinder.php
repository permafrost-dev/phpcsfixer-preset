<?php

namespace Permafrost\PhpCsFixerRules\Finders;

use PhpCsFixer\Finder;

class LaravelPackageFinder extends BaseFinder
{
    /**
     * Creates a Finder class preconfigured for Laravel packages.
     *
     * @param string $baseDir
     *
     * @return \PhpCsFixer\Finder
     */
    public static function create(string $baseDir)
    {
        return BasicProjectFinder::create($baseDir)
            ->notName('*.blade.php')
            ->exclude([
                "$baseDir/resources",
            ])
            ->in(static::onlyExistingPaths([
                "$baseDir/src",
                "$baseDir/tests",
                "$baseDir/config",
            ]));
    }
}
