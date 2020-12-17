<?php

namespace Permafrost\PhpCsFixerRules\Finders;

use PhpCsFixer\Finder;

class BasicProjectFinder extends BaseFinder
{
    /**
     * Creates a Finder class preconfigured for standard composer-based projects.
     *
     * @param string $baseDir
     *
     * @return \PhpCsFixer\Finder
     */
    public static function create(string $baseDir)
    {
        return Finder::create()
            ->ignoreVCS(true)
            ->ignoreDotFiles(true)
            ->name('*.php')
            ->exclude([
                "$baseDir/vendor",
            ]);
    }

    public static function configTypes(): array
    {
        return [
            'project',
        ];
    }
}
