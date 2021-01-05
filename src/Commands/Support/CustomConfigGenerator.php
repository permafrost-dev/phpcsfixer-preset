<?php

namespace Permafrost\PhpCsFixerRules\Commands\Support;

use Permafrost\PhpCsFixerRules\Support\Collection;

class CustomConfigGenerator extends ConfigGenerator
{
    /** @var array|Collection $includePaths */
    public $includePaths;

    /** @var array|Collection $excludePaths */
    public $excludePaths;

    public function __construct($includePaths = [], $excludePaths = [])
    {
        $this->setPaths($includePaths, $excludePaths);
    }

    public function setPaths($includePaths, $excludePaths)
    {
        $this->includePaths = $includePaths;
        $this->excludePaths = $excludePaths;
    }

    public function generateFinderCode(): string
    {
        $includeCode = trim(Collection::create($this->includePaths)
            ->map(function ($item) {
                return "        __DIR__ . '/$item',";
            })
            ->implode(PHP_EOL));

        $excludeCode = Collection::create($this->excludePaths)
            ->map(function ($item) {
                return "    ->notPath('$item/*')";
            })
            ->implode(PHP_EOL);

        if (!empty(trim($excludeCode))) {
            $excludeCode = PHP_EOL . $excludeCode;
        }

        return <<<CODE
        Finder::create()
            ->ignoreVCS(true)
            ->ignoreDotFiles(true)
            ->name('*.php')
            ->in([
                {$includeCode}
            ]){$excludeCode}
            ->exclude(__DIR__ . '/vendor')
        CODE;
    }
}
