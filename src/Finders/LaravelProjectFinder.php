<?php

namespace Permafrost\PhpCsFixerRules\Finders;

use PhpCsFixer\Finder;

class LaravelProjectFinder extends BaseFinder
{
    /**
     * Creates a Finder class preconfigured for Laravel projects.
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
                "$baseDir/bootstrap",
                "$baseDir/public",
                "$baseDir/resources",
                "$baseDir/storage",
            ])
            ->in(static::onlyExistingPaths([
                "$baseDir/app",
                "$baseDir/config",
                "$baseDir/database",
                "$baseDir/routes",
                "$baseDir/tests",
            ]));
    }

    public static function configTypes(): array
    {
        return [
            'laravel',
            'laravel:project',
        ];
    }
}
