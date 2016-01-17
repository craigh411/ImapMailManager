<?php

namespace Humps\MailManager\Collections;

use ArrayIterator;
use Countable;
use Humps\MailManager\Collections\Contracts\Collectable;
use Humps\MailManager\Collections\Contracts\Collection;
use Humps\MailManager\Contracts\Jsonable;
use IteratorAggregate;
use JsonSerializable;
use Traversable;

/**
 * A simple abstract class for allowing access to a collection of objects. This is not intended to be a comprehensive
 * Collection class, but rather, a convenient abstract wrapper for ensuring that the correct object arrays are
 * being passed in to functions which type hint child classes.
 *
 * @package Humps\MailManager\Collections
 */
abstract class AbstractCollection implements IteratorAggregate, Countable, Jsonable, Collection, JsonSerializable
{

    protected $collection;

    public function __construct()
    {
        $this->collection = [];
    }

    /**
     * Adds a Collectable to the collection
     * @param Collectable $item
     * @param null $key
     * @return void
     */
    public function add(Collectable $item, $key = null)
    {
        if (!is_null($key)) {
            $this->collection[$key] = $item;
        } else {
            $this->collection[] = $item;
        }
    }


    /**
     * Removes the Collectable from the collection at the given position
     * @param $key
     * @return void
     */
    public function remove($key)
    {
        if (array_key_exists($key, $this->collection)) {
            unset($this->collection[$key]);
        }
    }

    /**
     * Gets the Collectable for the given key
     * @param $key
     * @return Collectable
     */
    public function get($key)
    {
        return $this->collection[$key];
    }

    /**
     * Retrieve an external iterator
     * @link http://php.net/manual/en/iteratoraggregate.getiterator.php
     * @return Traversable An instance of an object implementing <b>Iterator</b> or
     * <b>Traversable</b>
     */
    public function getIterator()
    {
        return new ArrayIterator($this->collection);
    }

    /**
     * Count elements of an object
     * @link http://php.net/manual/en/countable.count.php
     * @return int The custom count as an integer.
     * The return value is cast to an integer.
     */
    public function count()
    {
        return count($this->collection);
    }

    /**
     * Returns the json representation of the collection
     * @return mixed
     */
    public function toJson()
    {
        return json_encode($this);
    }

    /**
     * Returns the json representation of the collection
     * @return string
     */
    public function __toString()
    {
        return $this->toJson();
    }

    /**
     * Clone each object in the collection
     */
    public function __clone()
    {
        if (count($this->collection)) {
            foreach ($this->collection as $key => &$collectable) {
                $this->collection[$key] = clone $collectable;
            }
        }
    }

    public function jsonSerialize()
    {
        return [
            'collection' => $this->collection
        ];
    }

}