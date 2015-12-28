<?php

namespace Humps\MailManager\Collections;

use InvalidArgumentException;
use Humps\MailManager\EmailAddress;

/**
 * A Collection of EmailAddress objects. This Collection can be accessed in exactly the same way as
 * a standard array.
 *
 * Class EmailCollection
 * @package Humps\MailManager\Collections
 */
class EmailCollection extends AbstractCollection
{

    function __construct()
    {
        parent::__construct();
    }

    /**
     * Adds an email address to the collection
     *
     * @param EmailAddress $email
     * @param null $key
     */
    public function add(Collectable $email, $key = null)
    {
        if ($email instanceof EmailAddress) {
            parent::add($email, $key);
        }else {
            throw new InvalidArgumentException('EmailAddress object expected');
        }
    }

    /**
     * Converts the collection to a delimited string.
     * @param string $delimiter The delimiter used to seperate each email address
     * @return string
     */
    public function implodeEmails($delimiter = ', ')
    {
        return implode($delimiter, array_map(function ($collection) {
            return $collection->getEmailAddress();
        }, $this->collection));
    }

    public function jsonSerialize()
    {
        return [
            'emails' => $this->collection
        ];
    }
}