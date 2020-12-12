<?php

namespace Permafrost\PhpCsFixerRules\Finders;

abstract class BaseFinder
{
    public static function onlyExistingPaths(array $paths): array
    {
        return array_filter($paths, function ($path) {
            return file_exists($path) && is_dir($path);
        });
    }

    abstract public static function create(string $baseDir);

    abstract public static function configTypes(): array;
}
