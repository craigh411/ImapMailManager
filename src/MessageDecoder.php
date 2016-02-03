<?php


namespace Humps\MailManager;

use Humps\MailManager\Contracts\Decoder;

/**
 * Attempts to decode Email messages.
 * Class Decoder
 * @package Humps\MailManager]
 */
class MessageDecoder implements Decoder
{
    /**
     * Attempts to Decode the message body. If a valid encoding is not passed then it will attempt to detect the encoding itself.
     * @param $body
     * @param int|null $encoding
     * @return string
     */
    public function decodeBody($body, $encoding = null)
    {
        switch ($encoding) {
            case ENCBASE64:
                return imap_base64($body);
            case ENCQUOTEDPRINTABLE:
                return imap_qprint($body);
            case ENCBINARY:
                return $body;
            default:
                // Let's check if the message is base64
                if ($decoded = base64_decode($body, true)) {
                    return $decoded;
                }

                if ($this->isQuotedPrintable($body)) {
                    return imap_qprint($body);
                }

                return $body;
        }
    }

	/**
	 * Checks to see if this is a quoted printable string
	 * @param string $message
	 * @return bool
	 */
    protected function isQuotedPrintable($message)
    {
        // Check for an invalid quoted printable sequence (= with non hex values following)
       return !preg_match("/=[^a-f0-9\n\r]{2}/i", $message, $matches);
    }

    /**
     * Decodes the given email header
     * @param string $header
	 * @param string $encoding The encoding to output the header in (Default: UTF-8)
     * @return string
     */
    public function decodeHeader($header, $encoding = "UTF-8")
    {
        $header = imap_mime_header_decode($header);

        $encodings = mb_list_encodings();
        // Add simplified Chinese (GB2312), supported but not listed and widely used.
        $encodings[] = 'GB2312';
        // Make all strings upper case for comparison to charset.
        $encodings = array_map('strtoupper', $encodings);

        $encoded = [];
        if (count($header)) {
            foreach ($header as $h) {
                if (in_array(strtoupper($h->charset), $encodings)) {
                    $encoded[] = mb_convert_encoding($h->text, $encoding, $h->charset);
                } else {
                    // We don't know what the charset is, so let's attempt to detect and convert the encoding.
                    $encoded[] = mb_convert_encoding($h->text, $encoding);
                }
            }
        }
        return implode('', $encoded);
    }
}
