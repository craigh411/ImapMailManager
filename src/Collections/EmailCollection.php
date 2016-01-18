<?php

namespace Humps\MailManager\Collections;

use Humps\MailManager\Collections\Contracts\Collectable;
use InvalidArgumentException;
use Humps\MailManager\Components\EmailAddress;

/**
 * A Collection of EmailAddress objects.
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
     * Adds an EmailAddress object to the collection
     * @param Collectable $email
     * @param string $key
     * @throws InvalidArgumentException
     * @return void
     */
    public function add(Collectable $email, $key = null)
    {
        if ($email instanceof EmailAddress) {
            parent::add($email, $key);
        } else {
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
}