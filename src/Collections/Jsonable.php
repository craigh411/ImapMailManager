<?php


namespace Humps\MailManager\Collections;


interface Jsonable
{
    /**
     * Converts the current object to JSON
     * @return mixed
     */
    public function toJson();
}