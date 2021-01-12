<?php

namespace Permafrost\PhpCsFixerRules\Support;

use Permafrost\PhpCsFixerRules\Support\Traits\IsArrayable;

class Collection implements \Countable, \ArrayAccess
{
    use IsArrayable;

    protected $items = [];

    public function __construct(array $items)
    {
        $this->items = $items;
    }

    public static function create(array $items): self
    {
        return new static($items);
    }

    public function toArray(): array
    {
        return $this->items;
    }

    public function push(...$items): self
    {
        foreach($items as $item) {
            $this->items[] = $item;
        }

        return $this;
    }

    public function exclude(array $items): self
    {
        return $this->filter(function ($value) use ($items) {
            return !in_array($value, $items);
        })->values();
    }

    public function filter($callback): self
    {
        return new static(array_filter($this->items, $callback));
    }

    public function implode(string $glue): string
    {
        return implode($glue, $this->toArray());
    }

    public function map($callback): self
    {
        return new static(array_map($callback, $this->items));
    }

    public function values(): self
    {
        return new static(array_values($this->items));
    }
}
