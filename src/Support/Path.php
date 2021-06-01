<?php

namespace Permafrost\PhpCsFixerRules\Support;

class Path
{
    public static function getSubDirectoryNames(string $path, array $excludeNames = []): Collection
    {
        if (!file_exists($path) || !is_dir($path)) {
            return Collection::create([]);
        }

        $files = scandir($path);

        return Collection::create($files)
            ->exclude(['.', '..'])
            ->exclude($excludeNames)
            ->filter(function($item) {
                return is_dir($item);
            })
            ->values();
    }
}
