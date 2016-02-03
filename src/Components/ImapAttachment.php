<?php
namespace Humps\MailManager\Components;

use Humps\MailManager\Collections\Contracts\Collectable;
use Humps\MailManager\Components\Contracts\Attachment;
use Humps\MailManager\Contracts\Jsonable;
use JsonSerializable;
use stdClass;

class ImapAttachment implements Collectable, JsonSerializable, Attachment, Jsonable
{

	protected $filename;
	protected $part;
	protected $encoding;
	protected $attachment;

	function __construct($filename, $part, $encoding, array $attachment = []) {
		$this->filename = $filename;
		$this->part = $part;
		$this->encoding = $encoding;
		$this->attachment = $attachment;
	}

	/**
	 * Returns the name of the file
	 * @return string
	 */
	public function getFilename() {
		return $this->filename;
	}

	/**
	 * Sets the name of the file
	 * @param string $filename
	 */
	public function setFilename($filename) {
		$this->filename = $filename;
	}

	/**
	 * Returns the part number of the attachment
	 * @return string
	 */
	public function getPart() {
		return $this->part;
	}

	/**
	 * Sets the part number of the attachment
	 * @param string $part
	 */
	public function setPart($part) {
		$this->part = $part;
	}

	/**
	 * Returns the encoding, see: <a href="http://php.net/manual/en/function.imap-fetchstructure.php">http://php.net/manual/en/function.imap-fetchstructure.php</a>
	 * for constants
	 * @return int
	 */
	public function getEncoding() {
		return $this->encoding;
	}

	/**
	 * Sets the encoding for the attachment
	 * @param int $encoding
	 */
	public function setEncoding($encoding) {
		$this->encoding = $encoding;
	}

	/**
	 * Sets the attachment array.
	 * @param array $attachment
	 */
	public function setAttachment(array $attachment) {
		$this->attachment = $attachment;
	}

	/**
	 * Returns all the attachment details returned from the server as an array.
	 * @return array
	 */
	public function getAttachment() {
		return $this->attachment;
	}

	/**
	 * Factory method for creating a new attachment object
	 * @param array|object $attachment
	 * @return static
	 */
	public static function create($attachment) {
		if($attachment instanceof stdClass) {
			$attachment = (array)$attachment;
		}
		$filename = (isset($attachment['filename'])) ? $attachment['filename'] : null;
		$part = (isset($attachment['part'])) ? $attachment['part'] : null;
		$encoding = (isset($attachment['encoding'])) ? $attachment['encoding'] : null;

		return new static($filename, $part, $encoding, $attachment);
	}

	/**
	 * Specify data which should be serialized to JSON
	 * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
	 * @return mixed data which can be serialized by <b>json_encode</b>,
	 * which is a value of any type other than a resource.
	 */
	function jsonSerialize() {
		return [
			'filename' => $this->filename,
			'part'     => $this->part,
			'encoding' => $this->encoding
		];
	}

	/**
	 * Converts the current object to JSON
	 * @return string
	 */
	public function toJson() {
		return json_encode($this);
	}

	public function __toString() {
		return $this->toJson();
	}
}