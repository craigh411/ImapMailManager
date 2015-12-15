<?php


namespace Humps\MailManager;

use Exception;

/**
 * Attempts to decode Email messages with unknown decoding
 * Class Decoder
 * @package Humps\MailManager]
 */
class EmailDecoder
{
    protected $message;

    function __construct($message)
    {
        $this->message = $message;
    }

    public function decode()
    {

        // Was it a binary message
        if ($decoded = base64_decode(imap_binary($this->message), true)) {
            return trim($decoded);
        }

        // Let's check if the message is base64
        if ($decoded = base64_decode($this->message, true)) {
            return $decoded;
        }

        // Lets decode manually, it's 7Bit or quoted printable, but PHP's native quote_printable_decode can return errors
        return $this->decode7Bit();


    }

    /**
     * Deals with 7bit encoded messages, it's essentially doing a quoted printable decode without throwing errors
     * @return mixed
     */
    public function decode7Bit()
    {
        // Pick up all equal signs followed by hex values
        preg_match_all("/\=[a-f0-9]{2}/i", $this->message, $encodedChars);

        $message = $this->message;

        // Remove equals from end of line (soft-break)
        $message = preg_replace("/=\r\n/", '', $message);
        // tab
        $message = preg_replace("/09=^/", "\t", $message);
        // space
        $message = preg_replace("/20=^/", " ", $message);


        foreach ($encodedChars[0] as $char) {
            $hex = str_replace("=^", '', $char);
            $decimal = hexdec($hex);
            $message = preg_replace("/$char/", chr($decimal), $message);
        }

        return trim($message);
    }

    public function guessEncoding()
    {
        return mb_detect_encoding($this->message, 'BASE64, 8bit');
    }

}
