<?php

namespace Permafrost\PhpCsFixerRules\Support;

class Path
{
    //'.', '..', '.git', '.github', '.idea', '.vscode', 'node_modules', 'vendor',

    public static function getSubDirectoryNames(string $path, array $excludeNames = []): Collection
    {
        if (!file_exists($path) || !is_dir($path)) {
            return Collection::create([]);
        }

        $files = scandir($path);

        if (!$files) {
            $files = [];
        }

        return Collection::create($files)
            ->exclude(['.', '..'])
            ->exclude($excludeNames)
            ->filter(function ($item) {
                return is_dir($item);
            })
            ->values();
    }
}
