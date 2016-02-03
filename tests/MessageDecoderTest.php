<?php
/**
 * Created by PhpStorm.
 * User: Craig
 * Date: 23/01/16
 * Time: 12:18
 */

namespace Humps\MailManager\Tests;


use Humps\MailManager\MessageDecoder;

class MessageDecoderTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @test
     */
    public function it_decodes_a_quoted_printable_string_when_encoding_not_set()
    {
        $decoder = new MessageDecoder();
        // Let's use all Latin-1 chars.
        $chars = '!"#$%&\'()*+,-./0123456789:;<=>?@ABCDEFGHIJKLMNOPQRSTUVWXYZ[\]^_`abcdefghijklmnopqrstuvwxyz{|}~€‚ƒ„…†‡ˆ‰Š‹ŒŽ‘’“”•–—˜™š›œžŸ ¡¢£¤¥¦§¨©ª«¬­®¯°±²³´µ¶·¸¹º»¼½¾¿ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖ×ØÙÚÛÜÝÞßàáâãäåæçèéêëìíîïðñòóôõö÷øùúûüýþÿ';
        $qp = quoted_printable_encode($chars);
        $decoded = $decoder->decodeBody($qp);

        $this->assertEquals($chars, $decoded);
    }

    /**
     * @test
     */
    public function it_decodes_a_base64_encoded_string_when_encoding_not_set()
    {
        $decoder = new MessageDecoder();
        // Let's use all Latin-1 chars.
        $chars = '!"#$%&\'()*+,-./0123456789:;<=>?@ABCDEFGHIJKLMNOPQRSTUVWXYZ[\]^_`abcdefghijklmnopqrstuvwxyz{|}~€‚ƒ„…†‡ˆ‰Š‹ŒŽ‘’“”•–—˜™š›œžŸ ¡¢£¤¥¦§¨©ª«¬­®¯°±²³´µ¶·¸¹º»¼½¾¿ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖ×ØÙÚÛÜÝÞßàáâãäåæçèéêëìíîïðñòóôõö÷øùúûüýþÿ';
        $encoded = base64_encode($chars);
        $decoded = $decoder->decodeBody($encoded);

        $this->assertEquals($chars, $decoded);
    }

    /**
     * @test
     */
    public function it_decodes_a_binary_string_when_encoding_not_set()
    {
        $decoder = new MessageDecoder();
        // Let's use all Latin-1 chars. This also has an invalid quoted printable sequence.
        $chars = '!"#$%&\'()*+,-./0123456789:;<=>?@ABCDEFGHIJKLMNOPQRSTUVWXYZ[\]^_`abcdefghijklmnopqrstuvwxyz{|}~€‚ƒ„…†‡ˆ‰Š‹ŒŽ‘’“”•–—˜™š›œžŸ ¡¢£¤¥¦§¨©ª«¬­®¯°±²³´µ¶·¸¹º»¼½¾¿ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖ×ØÙÚÛÜÝÞßàáâãäåæçèéêëìíîïðñòóôõö÷øùúûüýþÿ';
        $decoded = $decoder->decodeBody($chars);

        $this->assertEquals($chars, $decoded);
    }

    /**
     * @test
     */
    public function it_decodes_a_quoted_printable_string_when_encoding_is_set()
    {
        $decoder = new MessageDecoder();
        // Let's use all Latin-1 chars.
        $chars = '!"#$%&\'()*+,-./0123456789:;<=>?@ABCDEFGHIJKLMNOPQRSTUVWXYZ[\]^_`abcdefghijklmnopqrstuvwxyz{|}~€‚ƒ„…†‡ˆ‰Š‹ŒŽ‘’“”•–—˜™š›œžŸ ¡¢£¤¥¦§¨©ª«¬­®¯°±²³´µ¶·¸¹º»¼½¾¿ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖ×ØÙÚÛÜÝÞßàáâãäåæçèéêëìíîïðñòóôõö÷øùúûüýþÿ';
        $qp = quoted_printable_encode($chars);
        $decoded = $decoder->decodeBody($qp, ENCQUOTEDPRINTABLE);

        $this->assertEquals($chars, $decoded);
    }

    /**
     * @test
     */
    public function it_decodes_a_base64_encoded_string_when_encoding_is_set()
    {
        $decoder = new MessageDecoder();
        // Let's use all Latin-1 chars.
        $chars = '!"#$%&\'()*+,-./0123456789:;<=>?@ABCDEFGHIJKLMNOPQRSTUVWXYZ[\]^_`abcdefghijklmnopqrstuvwxyz{|}~€‚ƒ„…†‡ˆ‰Š‹ŒŽ‘’“”•–—˜™š›œžŸ ¡¢£¤¥¦§¨©ª«¬­®¯°±²³´µ¶·¸¹º»¼½¾¿ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖ×ØÙÚÛÜÝÞßàáâãäåæçèéêëìíîïðñòóôõö÷øùúûüýþÿ';
        $encoded = base64_encode($chars);
        $decoded = $decoder->decodeBody($encoded, ENCBASE64);

        $this->assertEquals($chars, $decoded);
    }

    /**
     * @test
     */
    public function it_decodes_a_binary_string_when_encoding_is_set()
    {
        $decoder = new MessageDecoder();
        // Let's use all Latin-1 chars. This also has an invalid quoted printable sequence.
        $chars = '!"#$%&\'()*+,-./0123456789:;<=>?@ABCDEFGHIJKLMNOPQRSTUVWXYZ[\]^_`abcdefghijklmnopqrstuvwxyz{|}~€‚ƒ„…†‡ˆ‰Š‹ŒŽ‘’“”•–—˜™š›œžŸ ¡¢£¤¥¦§¨©ª«¬­®¯°±²³´µ¶·¸¹º»¼½¾¿ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖ×ØÙÚÛÜÝÞßàáâãäåæçèéêëìíîïðñòóôõö÷øùúûüýþÿ';
        $decoded = $decoder->decodeBody($chars, ENCBINARY);

        $this->assertEquals($chars, $decoded);
    }

    /**
     * @test
     */
    public function it_converts_the_header_to_a_UTF_8_string()
    {
        $header = '=?utf-8?Q?Foo=20Bar=20Baz=20for=2001=2F17=2F2016?=';
        $decoder = new MessageDecoder();
        $decoded = $decoder->decodeHeader($header);
        $this->assertEquals('Foo Bar Baz for 01/17/2016', $decoded);
        $this->assertEquals('UTF-8', mb_detect_encoding($decoded, "UTF-8", true));
    }

    /**
     * @test
     */
    public function it_converts_the_header_to_a_UTF_8_string_when_encoding_unknown()
    {
        $header = '=?default?Q?Foo=20Bar=20Baz=20for=2001=2F17=2F2016?=';
        $decoder = new MessageDecoder();
        $decoded = $decoder->decodeHeader($header);
        $this->assertEquals('Foo Bar Baz for 01/17/2016', $decoded);
        $this->assertEquals('UTF-8', mb_detect_encoding($decoded, "UTF-8", true));
    }

}
