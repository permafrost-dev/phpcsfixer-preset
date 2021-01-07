<?php

namespace Permafrost\PhpCsFixerRules\Support\Traits;

trait IsArrayable
{
    public function count(): int
    {
        return count($this->items);
    }

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

}
