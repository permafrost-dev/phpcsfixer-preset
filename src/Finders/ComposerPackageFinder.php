<?php

namespace Permafrost\PhpCsFixerRules\Finders;

use PhpCsFixer\Finder;

class ComposerPackageFinder extends BaseFinder
{
    /**
     * Creates a Finder class preconfigured for Composer packages.
     *
     * @param string $baseDir
     *
     * @return \PhpCsFixer\Finder
     */
    public static function create(string $baseDir)
    {
        return BasicProjectFinder::create($baseDir)
            ->in(static::onlyExistingPaths([
                "$baseDir/src",
                "$baseDir/tests",
            ]));
    }

    public static function configTypes(): array
    {
        return [
            'package',
        ];
    }
}
