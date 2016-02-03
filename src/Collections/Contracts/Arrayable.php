<?php
namespace Humps\MailManager\Collections\Contracts;

interface Arrayable
{

	/**
	 * Returns the collection as an array
	 * @return array
	 */
	public function toArray();
}