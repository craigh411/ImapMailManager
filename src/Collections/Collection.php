<?php

namespace Humps\MailManager\Collections;

use ArrayAccess;
use ArrayIterator;
use Countable;
use IteratorAggregate;

/**
 * Class Collection
 * @package Humps\MailManager\Collections
 *
 * A simple abstract class for allowing access to an array of objects. This is not intended to be a comprehensive
 * Collection class, but rather, a convenient abstract wrapper for ensuring that the correct object arrays are
 * being passed in to functions which type hint child classes.
 */
abstract class Collection implements IteratorAggregate, ArrayAccess, Countable, Jsonable
{

    protected $collection;

    protected function __construct()
    {
        $this->collection = [];
    }

    /**
     * A protected method for adding a Collectable to the Collection. This is required
     * as php does not allow abstract class methods to be overriden by methods with
     * different parameter types, which would mean we would have an array of Collectable objects
     * rather than their concrete implementations.
     *
     * @param Collectable $item
     * @param null $key
     */
    protected function addCollectable(Collectable $item, $key = null)
    {
        if ($key) {
            $this->collection[] = $item;
        } else {
            $this->collection[] = $item;
        }
    }

    public function remove($key)
    {
        if (array_key_exists($key, $this->collection)) {
            unset($this->collection[$key]);
        }
    }

    public function get($key)
    {
        return $this->collection[$key];
    }

    public function getIterator()
    {
        return new ArrayIterator($this->collection);
    }

    public function count()
    {
        return count($this->collection);
    }

    public function offsetExists($offset)
    {
        return array_key_exists($offset, $this->collection);
    }

    public function offsetGet($offset)
    {
        return $this->collection[$offset];
    }

    public function offsetSet($offset, $value)
    {
        if (is_null($offset)) {
            $this->collection[] = $value;
        } else {
            $this->collection[$offset] = $value;
        }
    }

    public function offsetUnset($offset)
    {
        unset($this->collection[$offset]);
    }

    public function toJson()
    {
        return json_encode($this->collection);
    }

    public function __toString()
    {
        return $this->toJson();
    }
}