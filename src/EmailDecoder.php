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


    public static function decodeBody($message)
    {

        // Was it a binary message
        if ($decoded = base64_decode(imap_binary($message), true)) {
            return trim($decoded);
        }

        // Let's check if the message is base64
        if ($decoded = base64_decode($message, true)) {
            return $decoded;
        }

        // Lets decode manually, but it's probably ASCII!
        return self::decodeQP($message);


    }

    /**
     * A quoted printable decode without throwing the errors that PHP's native function can throw.
     * If you've made it here, it probably ASCII, but this will sort out anything
     * that's got through.
     *
     * @return string
     */
    public static function decodeQP($message)
    {
        // Pick up all equal signs followed by hex values
        preg_match_all("/\=[a-f0-9]{2}/i", $message, $encodedChars);

        // Remove equals from end of line (soft-break)
        $message = preg_replace("/=\r\n/", '', $message);

        /**
         * Space and tab can also have the following encoding, so we need to check for them.
         */

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

    /**
     * Decodes the given email header
     * @param $header
     * @return string
     */
    public static function decodeHeader($header)
    {
        $header = imap_mime_header_decode($header);
        $str = '';

        $encodings = mb_list_encodings();
        // Add simplified Chinese (GB2312), supported but not listed and widely used.
        $encodings[] = 'GB2312';

        // Make all strings upper case for comparison, charsets are presented
        // with different case and we are using in_array() for comparison
        $encodings = array_map(function ($encoding) {
            return strtoupper($encoding);
        }, $encodings);


        // A bit hacky, but imap_mime_header_decode() doesn't map the '£' symbol from ISO-8859-1,
        // so ISO-8859-1 strings need to be run through utf8_encode() for correct display.
        if (count($header)) {
            foreach ($header as $h) {
                if (strtoupper($h->charset) === "ISO-8859-1" || !in_array($h->charset, $encodings)) {
                    $str .= utf8_encode($h->text);
                } else {
                    $str .= mb_convert_encoding($h->text, 'utf-8', $h->charset);
                }
            }
        }
        return $str;
    }

    public static function guessEncoding($message)
    {
        return mb_detect_encoding($message, 'BASE64, 8bit');
    }

}
