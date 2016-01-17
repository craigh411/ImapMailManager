<?php

namespace Humps\MailManager\Collections\Contracts;

/**
 * @package Humps\MailManager\Collections
 */
interface Collection
{
    /**
     * Adds a Collectable to the collection
     * @param Collectable $item
     * @param string $key
     * @return void
     */
    public function add(Collectable $item, $key = null);

    /**
     * Removes a Collectable from the collection by the given key
     * @param $key
     * @return void
     */
    public function remove($key);

    /**
     * Returns the Collectable by the given key
     * @param $key
     * @return Collectable
     */
    public function get($key);
}