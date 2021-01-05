<?php

namespace Permafrost\PhpCsFixerRules\Support;

class Collection implements \Countable, \ArrayAccess
{
    protected $items = [];

    public function __construct($items)
    {
        $this->items = $this->prepareItemsForConstructor($items);
    }

    protected function prepareItemsForConstructor($items): array
    {
        if ($items instanceof self) {
            return $items->toArray();
        }

        if (!is_array($items)) {
            return [$items];
        }

        return $items;
    }

    public static function create($items): self
    {
        return new static($items);
    }

    public function toArray(): array
    {
        return $this->items;
    }

    public function count(): int
    {
        return count($this->items);
    }

    /*
    public function current()
    {
        // TODO: Implement current() method.
    }

    public function key()
    {
        // TODO: Implement key() method.
    }

    public function next()
    {
        // TODO: Implement next() method.
    }

    public function rewind()
    {
        // TODO: Implement rewind() method.
    }

    public function valid()
    {
        // TODO: Implement valid() method.
    }*/

    /**
     * @codeCoverageIgnore
     *
     * @param mixed $offset
     *
     * @return bool
     */
    public function offsetExists($offset)
    {
        return isset($this->items[$offset]);
    }

    /**
     * @codeCoverageIgnore
     *
     * @param mixed $offset
     *
     * @return mixed
     */
    public function offsetGet($offset)
    {
        return $this->items[$offset];
    }

    /**
     * @codeCoverageIgnore
     *
     * @param mixed $offset
     * @param mixed $value
     */
    public function offsetSet($offset, $value)
    {
        $this->items[$offset] = $value;
    }

    /**
     * @codeCoverageIgnore
     *
     * @param mixed $offset
     */
    public function offsetUnset($offset)
    {
        unset($this->items[$offset]);
    }

    /**
     * Pushes each item in `$items` onto the end of the collection.
     *
     * @param mixed ...$items
     *
     * @return static
     */
    public function push(...$items): self
    {
        $items = array_merge($this->items, $items);

        return new static($items);
    }

    /**
     * Appends an item to the end of the collection.
     *
     * @param $item
     *
     * @return static
     */
    public function append($item): self
    {
        $items = $this->items;
        $items[] = $item;

        return new static($items);
    }

    /**
     * Returns true if `$item` exists as a value in the collection.
     *
     * @param mixed $item
     *
     * @return bool
     */
    public function contains($item): bool
    {
        return in_array($item, $this->values()->toArray());
    }

    /**
     * Calls the function `$callback` for each item in the collection, passing the individual item as
     * the callback parameter.
     *
     * @param $callback
     *
     * @return static
     */
    public function each($callback): self
    {
        foreach ($this->items as $item) {
            $callback($item);
        }

        return $this;
    }

    /**
     * @param array|Collection $items
     * @param bool $strict
     *
     * @return static
     */
    public function exclude($items, bool $strict = true): self
    {
        if ($items instanceof Collection) {
            $items = $items->toArray();
        }

        if (empty($items)) {
            return new static($this->items);
        }

        return new static(
            array_values($this->filter(function ($value) use ($items, $strict) {
                return !in_array($value, $items, $strict);
            })->toArray())
        );
    }

    public function filter($callback): self
    {
        return new static(array_filter($this->items, $callback, ARRAY_FILTER_USE_BOTH));
    }

    public function implode(string $glue): string
    {
        return implode($glue, $this->toArray());
    }

    public function map($callback): self
    {
        $items = array_map($callback, $this->items);

        return new static($items);
    }

    public function reject($callback): self
    {
        $cb = function ($item, $value) use ($callback) {
            return !$callback($item, $value);
        };

        return $this->filter($cb);
    }

    public function values(): self
    {
        return new static(array_values($this->items));
    }
}
