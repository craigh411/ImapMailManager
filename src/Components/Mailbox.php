<?php
namespace Humps\MailManager\Components;

use Humps\MailManager\Contracts\Jsonable;
use JsonSerializable;

class Mailbox implements JsonSerializable, Jsonable
{

	protected $server;
	protected $username;
	protected $password;
	protected $port;
	protected $folder;
	protected $ssl;
	protected $validateCert;

	/**
	 * Mailbox constructor.
	 * @param $server
	 * @param $username
	 * @param $password
	 * @param null $port
	 * @param string $folder
	 * @param bool $ssl
	 * @param bool $validateCert
	 */
	public function __construct($server, $username, $password, $port = null, $folder = 'INBOX', $ssl = true, $validateCert = true) {
		$this->server = $server;
		$this->username = $username;
		$this->password = $password;
		$this->port = $port;
		$this->folder = $folder;
		$this->ssl = $ssl;
		$this->validateCert = $validateCert;
	}

	/**
	 * Returns true if this is an SSL mailbox
	 * @return boolean
	 */
	public function isSsl() {
		return $this->ssl;
	}

	/**
	 * Sets whether this is an SSL mailbox
	 * @param boolean $ssl
	 */
	public function setSsl($ssl) {
		$this->ssl = $ssl;
	}

	/**
	 * Returns true of the certificate should be validate
	 * @return boolean
	 */
	public function isValidateCert() {
		return $this->validateCert;
	}

	/**
	 * Sets whether the certificate should be validated
	 * @param boolean $validateCert
	 */
	public function setValidateCert($validateCert) {
		$this->validateCert = $validateCert;
	}

	/**
	 * Returns the the server for the mailbox
	 * @return string
	 */
	public function getServer() {
		return $this->server;
	}

	/**
	 * Sets the server for the mailbox (e.g imap.gmail.com)
	 * @param string $server
	 */
	public function setServer($server) {
		$this->server = $server;
	}

	/**
	 * Returns the username for the mailbox
	 * @return string
	 */
	public function getUsername() {
		return $this->username;
	}

	/**
	 * Sets the username for the mailbox
	 * @param string $username
	 */
	public function setUsername($username) {
		$this->username = $username;
	}

	/**
	 * Returns the password for the mailbox
	 * @return string
	 */
	public function getPassword() {
		return $this->password;
	}

	/**
	 * Sets the password of the mailbox
	 * @param string $password
	 */
	public function setPassword($password) {
		$this->password = $password;
	}

	/**
	 * Returns the port of the mailbox
	 * @return int
	 */
	public function getPort() {
		return $this->port;
	}

	/**
	 * Sets the port of the mailbox
	 * @param int $port
	 */
	public function setPort($port) {
		$this->port = $port;
	}

	/**
	 * Returns the folder
	 * @return array
	 */
	public function getFolder() {
		return $this->folder;
	}

	/**
	 * Sets the folder - This is the response from the imap call.
	 * @param array $folder
	 */
	public function setFolder($folder) {
		$this->folder = $folder;
	}

	/**
	 * Returns the mailbox string
	 * @param bool $excludeFolder
	 * @return string
	 */
	public function getMailboxName($excludeFolder = false) {
		$mailboxName = '{';
		$mailboxName .= $this->getServer();
		$mailboxName .= ($this->getPort()) ? ':' . $this->getPort() : '';
		$mailboxName .= ($this->isSsl()) ? '/imap/ssl' : '';
		$mailboxName .= (! $this->isValidateCert()) ? '/novalidate-cert' : '';
		$mailboxName .= '}';
		$mailboxName .= ($this->getFolder() && ! $excludeFolder) ? $this->getFolder() : '';

		return $mailboxName;
	}

	/**
	 * Converts the object to a string
	 * @return string
	 */
	public function __toString() {
		return $this->toJson();
	}

	/**
	 * Specify data which should be serialized to JSON
	 * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
	 * @return mixed data which can be serialized by <b>json_encode</b>,
	 * which is a value of any type other than a resource.
	 * @since 5.4.0
	 */
	function jsonSerialize() {
		return [
			'server'       => $this->server,
			'username'     => $this->username,
			'password'     => $this->password,
			'port'         => $this->port,
			'folder'       => $this->folder,
			'ssl'          => $this->ssl,
			'validateCert' => $this->validateCert
		];
	}

	/**
	 * Converts the current object to JSON
	 * @return string
	 */
	public function toJson() {
		return json_encode($this);
	}
}