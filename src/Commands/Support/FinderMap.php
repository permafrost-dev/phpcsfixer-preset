<?php

namespace Permafrost\PhpCsFixerRules\Commands\Support;

use Permafrost\PhpCsFixerRules\Finders\BasicProjectFinder;
use Permafrost\PhpCsFixerRules\Support\Str;

class FinderMap
{
    protected $map = [];

    public function __construct(array $finderClasses)
    {
        $this->mapTypesToClasses($finderClasses);
    }

    /**
     * @param string $name
     *
     * @return string
     */
    public function find(string $name): string
    {
        return $this->map[Str::snake($name)] ?? BasicProjectFinder::class;
    }

    /**
     * Maps config type names to their associated Finder classnames.
     *
     * @param array $finderClassnames
     */
    protected function mapTypesToClasses(array $finderClassnames): void
    {
        $this->map = [];

        foreach ($finderClassnames as $finderClass) {
            foreach ($finderClass::configTypes() as $configType) {
                $this->map[$configType] = $finderClass;
            }
        }
    }

    public function getMap(): array
    {
        return $this->map;
    }
}
