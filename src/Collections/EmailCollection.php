<?php

namespace Humps\MailManager\Collections;

use Humps\MailManager\EmailAddress;

class EmailCollection extends Collection
{

    function __construct()
    {
        parent::__construct();
    }

    public function add(EmailAddress $email, $key = null)
    {
        parent::addCollectable($email, $key);
    }

    /**
     * Converts the collection to a string.
     * @return string
     */
    public function implodeEmails($delimiter = ', ')
    {
        return implode($delimiter, array_map(function ($collection) {
            return $collection->getEmailAddress();
        }, $this->collection));
    }

    public function __clone()
    {
        $this->collection = array_map(function ($collection) {
            return clone $collection;
        }, $this->collection);
    }
}