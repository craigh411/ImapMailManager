<?php


namespace Humps\MailManager\Contracts;


interface Jsonable
{
    /**
     * Converts the current object to JSON
     * @return string
     */
    public function toJson();
}