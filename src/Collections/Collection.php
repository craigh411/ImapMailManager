<?php

namespace Humps\MailManager\Collections;

use ArrayAccess;
use ArrayIterator;
use Countable;
use IteratorAggregate;

abstract class Collection implements IteratorAggregate, ArrayAccess, Countable, Jsonable
{

    protected $collection;

    protected function __construct()
    {
        $this->collection = [];
    }

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