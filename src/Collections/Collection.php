<?php

namespace Humps\MailManager\Collections;


/**
 * @package Humps\MailManager\Collections
 */
interface Collection
{
    /**
     * @param Collectable $item
     * @param null $key
     */
    public function add(Collectable $item, $key = null);

    public function remove($key);

    public function get($key);
}