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
     * @param string $qpCharset The charset to use when converting quoted printable hex values.
     * @return string
     */
    public function decodeBody($body, $encoding = null, $qpCharset = 'cp1252')
    {
        switch ($encoding) {
            case ENCBASE64:
                return imap_base64($body);
            case ENCQUOTEDPRINTABLE:
                return static::decodeQP($body, $qpCharset);
            case ENCBINARY:
                return imap_binary($body);
            default:

                // Was it a binary message
                if ($decoded = base64_decode(imap_binary($body), true)) {
                    return trim($decoded);
                }

                // Let's check if the message is base64
                if ($decoded = base64_decode($body, true)) {
                    return $decoded;
                }

                // Let's pass it through the qp parser and return the result.
                return $this->decodeQP($body, $qpCharset);
        }
    }

    /**
     * A quoted printable decoder, that supports extended ASCII chars and doesn't thrown
     * errors like the native imap_qprint() function.
     * @param string $message
     * ~param string $charset The charset to use when converting quoted printable hex values.
     * @return string
     */
    public static function decodeQP($message, $charset = 'cp1252')
    {
        // Pick up all equal signs followed by hex values
        preg_match_all("/\=[a-f0-9]{2}/i", $message, $encodedChars);
        // Remove equals from end of line (soft-break)
        $message = preg_replace("/=\r\n/", '', $message);

        // Space and tab can also have the following encoding, so we need to check for them.
        $message = preg_replace("/09=^/", "\t", $message); // tab
        $message = preg_replace("/20=^/", " ", $message); // space

        foreach ($encodedChars[0] as $char) {
            $hex = str_replace("=^", '', $char);
            $decimal = hexdec($hex);
            $message = preg_replace("/$char/", static::convertChar($decimal, $charset), $message);
        }

        return trim($message);
    }


    /**
     * Converts the decimal value to a character, by default we are using the extended cp1252 charset to
     * allow extended ascii characters to be used.
     * @param $decimal
     * @param string $charset
     * @return string
     */
    protected static function convertChar($decimal, $charset = 'cp1252')
    {
        // These windows chars are not converted, so convert them manually
        if ($charset == 'cp1252') {
            switch ($decimal) {
                case 142:
                    return '&#142;'; // Ž
                case 158:
                    return '&#158;'; // ž
            }
        }

        return htmlentities(chr($decimal), null, $charset);
    }

    /**
     * Decodes the given email header
     * @param $header
     * @return string
     */
    public function decodeHeader($header)
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
                $charset = strtoupper($h->charset);
                if (strtoupper($charset) === "ISO-8859-1" || !in_array($charset, $encodings)) {
                    $str .= utf8_encode($h->text);
                } else {
                    $str .= mb_convert_encoding($h->text, 'UTF-8', $h->charset);
                }
            }
        }
        return $str;
    }
}
