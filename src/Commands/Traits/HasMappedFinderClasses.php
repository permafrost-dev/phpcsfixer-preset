<?php

namespace Permafrost\PhpCsFixerRules\Commands\Traits;

trait HasMappedFinderClasses
{
    /** @var array $finderConfigTypeMapCache */
    protected $finderConfigTypeMapCache = [];

    /**
     * @param string $type
     *
     * @return string
     */
    protected function determineCorrectFinder(string $type): string
    {
        $map = $this->finderConfigTypeMap();

        return $map[$type] ?? $map['default'];
    }

    /**
     * Create a map of config types and their associated finder class.
     * Returns cached data after the first call.
     *
     * ['type' => classname, ...]
     *
     * @return array
     */
    protected function finderConfigTypeMap(): array
    {
        if (!empty($this->finderConfigTypeMapCache)) {
            return $this->finderConfigTypeMapCache;
        }

        $result = [];

        foreach($this->finders() as $finderClass) {
            foreach($finderClass::configTypes() as $configType) {
                $result[$configType] = $finderClass;
            }
        }

        if (!isset($result['default'])) {
            $result['default'] = $this->finders()[0];
        }

        $this->finderConfigTypeMapCache = $result;

        return $result;
    }
}