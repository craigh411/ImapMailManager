<?php


namespace Humps\MailManager\Tests\Helpers;


use Humps\MailManager\Components\ImapBodyPart;
use Humps\MailManager\ImapMailManager;

/**
 * Overrides the relevant methods to mock an Email with embedded images, In this case this is a var_export of a real
 * Email sent by Google to warn that less secure apps can access your mailbox.
 *
 * Class ImapMailManagerEmbeddedImageHelper
 * @package Humps\MailManager\Tests\Helpers
 */
class ImapMailManagerEmbeddedImageHelper
{

    /**
     * Overrides the previous mocked fetch structure with one with embedded images
     * @param int $messageNo
     * @return object
     */
    public static function fetchStructure($messageNo = 1)
    {
        return (object)[
            'type'          => 1,
            'encoding'      => 0,
            'ifsubtype'     => 1,
            'subtype'       => 'RELATED',
            'ifdescription' => 0,
            'ifid'          => 0,
            'ifdisposition' => 0,
            'ifdparameters' => 0,
            'ifparameters'  => 1,
            'parameters'    => [
                0 => (object)[
                    'attribute' => 'BOUNDARY',
                    'value'     => '047d7b6700098d27710526b0e6b9',
                ],
            ],
            'parts'         => [
                0 => (object)[
                    'type'          => 1,
                    'encoding'      => 0,
                    'ifsubtype'     => 1,
                    'subtype'       => 'ALTERNATIVE',
                    'ifdescription' => 0,
                    'ifid'          => 0,
                    'ifdisposition' => 0,
                    'ifdparameters' => 0,
                    'ifparameters'  => 1,
                    'parameters'    => [
                        0 => (object)[
                            'attribute' => 'BOUNDARY',
                            'value'     => '047d7b6700098d276d0526b0e6b8',
                        ],
                    ],
                    'parts'         => [
                        0 => (object)[
                            'type'          => 0,
                            'encoding'      => 3,
                            'ifsubtype'     => 1,
                            'subtype'       => 'PLAIN',
                            'ifdescription' => 0,
                            'ifid'          => 0,
                            'lines'         => 20,
                            'bytes'         => 1568,
                            'ifdisposition' => 0,
                            'ifdparameters' => 0,
                            'ifparameters'  => 1,
                            'parameters'    => [
                                0 => (object)[
                                    'attribute' => 'CHARSET',
                                    'value'     => 'ISO-8859-1',
                                ],
                                1 => (object)[
                                    'attribute' => 'DELSP',
                                    'value'     => 'yes',
                                ],
                                2 => (object)[
                                    'attribute' => 'FORMAT',
                                    'value'     => 'flowed',
                                ],
                            ],
                        ],
                        1 => (object)[
                            'type'          => 0,
                            'encoding'      => 4,
                            'ifsubtype'     => 1,
                            'subtype'       => 'HTML',
                            'ifdescription' => 0,
                            'ifid'          => 0,
                            'lines'         => 59,
                            'bytes'         => 4666,
                            'ifdisposition' => 0,
                            'ifdparameters' => 0,
                            'ifparameters'  => 1,
                            'parameters'    => [
                                0 => (object)[
                                    'attribute' => 'CHARSET',
                                    'value'     => 'ISO-8859-1',
                                ],
                            ],
                        ],
                    ],
                ],
                1 => (object)[
                    'type'          => 5,
                    'encoding'      => 3,
                    'ifsubtype'     => 1,
                    'subtype'       => 'PNG',
                    'ifdescription' => 0,
                    'ifid'          => 1,
                    'id'            => '<wrench>',
                    'bytes'         => 6634,
                    'ifdisposition' => 1,
                    'disposition'   => 'INLINE',
                    'ifdparameters' => 0,
                    'ifparameters'  => 1,
                    'parameters'    => [
                        0 => (object)[
                            'attribute' => 'NAME',
                            'value'     => 'wrench.png',
                        ],
                    ],
                ],
                2 => (object)[
                    'type'          => 5,
                    'encoding'      => 3,
                    'ifsubtype'     => 1,
                    'subtype'       => 'PNG',
                    'ifdescription' => 0,
                    'ifid'          => 1,
                    'id'            => '<google_logo>',
                    'bytes'         => 11366,
                    'ifdisposition' => 1,
                    'disposition'   => 'INLINE',
                    'ifdparameters' => 0,
                    'ifparameters'  => 1,
                    'parameters'    => [
                        0 => (object)[
                            'attribute' => 'NAME',
                            'value'     => 'google_logo.png',
                        ],
                    ],
                ],
            ],
        ];
    }

    public static function fetchBody($messageNo = 1, $part = 1, $options = 0)
    {
        switch ($part) {
            case 2:

                return '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 QmCC';
            case 3:
                return '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';
        }

        return '<html lang=3D"en"><head><meta name=3D"format-detection" content=3D"date=3Dn=
o"><meta name=3D"format-detection" content=3D"email=3Dno"></head><body styl=
e=3D"margin: 0; padding: 0;" bgcolor=3D"#FFFFFF"><table width=3D"100%" heig=
ht=3D"100%" style=3D"min-width: 348px;" border=3D"0" cellspacing=3D"0" cell=
padding=3D"0"><tr height=3D"32px"></tr><tr align=3D"center"><td width=3D"32=
px"></td><td><table border=3D"0" cellspacing=3D"0" cellpadding=3D"0" style=
=3D"max-width: 600px;"><tr><td><table width=3D"100%" border=3D"0" cellspaci=
ng=3D"0" cellpadding=3D"0"><tr><td align=3D"left"><img width=3D"92" height=
=3D"32" src=3D"cid:google_logo" style=3D"display: block; width: 92px; heigh=
t: 32px;"></td><td align=3D"right"><img width=3D"32" height=3D"32" style=3D=
"display: block; width: 32px; height: 32px;" src=3D"cid:wrench"></td></tr><=
/table></td></tr><tr height=3D"16"></tr><tr><td><table bgcolor=3D"#E6E6E6" =
width=3D"100%" border=3D"0" cellspacing=3D"0" cellpadding=3D"0" style=3D"mi=
n-width: 332px; max-width: 600px; border: 1px solid #E0E0E0; border-bottom:=
 0; border-top-left-radius: 3px; border-top-right-radius: 3px;"><tr><td hei=
ght=3D"72px" colspan=3D"3"></td></tr><tr><td width=3D"32px"></td><td style=
=3D"font-family: Roboto-Regular,Helvetica,Arial,sans-serif; font-size: 24px=
; color: #000000; line-height: 1.25;">Access for less secure apps has been =
turned on</td><td width=3D"32px"></td></tr><tr><td height=3D"18px" colspan=
=3D"3"></td></tr></table></td></tr><tr><td><table bgcolor=3D"#FAFAFA" width=
=3D"100%" border=3D"0" cellspacing=3D"0" cellpadding=3D"0" style=3D"min-wid=
th: 332px; max-width: 600px; border: 1px solid #F0F0F0; border-bottom: 1px =
solid #C0C0C0; border-top: 0; border-bottom-left-radius: 3px; border-bottom=
-right-radius: 3px;"><tr height=3D"16px"><td width=3D"32px" rowspan=3D"3"><=
/td><td></td><td width=3D"32px" rowspan=3D"3"></td></tr><tr><td><table styl=
e=3D"min-width: 300px;" border=3D"0" cellspacing=3D"0" cellpadding=3D"0"><t=
r><td style=3D"font-family: Roboto-Regular,Helvetica,Arial,sans-serif; font=
-size: 13px; color: #202020; line-height: 1.5;">Hi Foobar,</td></tr><tr><td =
style=3D"font-family: Roboto-Regular,Helvetica,Arial,sans-serif; font-size:=
 13px; color: #202020; line-height: 1.5;">You recently changed your securit=
y settings so that your Google Account foobar@gmail.com is no longer=
 protected by modern security standards.<br><br>Please be aware that it is =
now easier for an attacker to break into your account. You can make your ac=
count safer again by undoing this change <a href=3D"https://www.google.com/=
settings/security/lesssecureapps" style=3D"text-decoration: none; color: #4=
285F4;" target=3D"_blank">here</a>, then switching to apps made by Google s=
uch as Gmail to access your account.<br><br><b>Don&#39;t recognize this act=
ivity?</b><br>Review your <a href=3D"https://accounts.google.com/AccountCho=
oser?Email=3Dfoobar@gmail.com&amp;continue=3Dhttps://security.google=
.com/settings/security/activity?rfn%3D28%26rfnc%3D1" style=3D"text-decorati=
on: none; color: #4285F4;" target=3D"_blank">recently used devices</a> now.=
</td></tr><tr height=3D"32px"></tr><tr><td style=3D"font-family: Roboto-Reg=
ular,Helvetica,Arial,sans-serif; font-size: 13px; color: #202020; line-heig=
ht: 1.5;">Best,<br>The Google Accounts team</td></tr><tr height=3D"16px"></=
tr><tr><td><table style=3D"font-family: Roboto-Regular,Helvetica,Arial,sans=
-serif; font-size: 12px; color: #B9B9B9; line-height: 1.5;"><tr><td>This em=
ail can\'t receive replies. For more information, visit the <a href=3D"https=
://support.google.com/accounts/answer/6010255" style=3D"text-decoration: no=
ne; color: #4285F4;" target=3D"_blank">Google Accounts Help Center</a>.</td=
><tr></table></td></tr></table></td></tr><tr height=3D"32px"></tr></table><=
/td></tr><tr height=3D"16"></tr><tr><td style=3D"max-width: 600px; font-fam=
ily: Roboto-Regular,Helvetica,Arial,sans-serif; font-size: 10px; color: #BC=
BCBC; line-height: 1.5;"><tr><td><table style=3D"font-family: Roboto-Regula=
r,Helvetica,Arial,sans-serif; font-size: 10px; color: #666666; line-height:=
 18px; padding-bottom: 10px"><tr><td>You received this mandatory email serv=
ice announcement to update you about important changes to your Google produ=
ct or account.</td></tr><tr><td><div style=3D"direction: ltr; text-align: l=
eft">&copy; 2015 Google Inc., 1600 Amphitheatre Parkway, Mountain View, CA =
94043, USA</div></td></tr></table></td></tr></td></tr></table></td><td widt=
h=3D"32px"></td></tr><tr height=3D"32px"></tr></table></body></html>';
    }


    public static function getBody()
    {
        return [
            0 => [
                0 => new ImapBodyPart(0, 3, 'PLAIN', '1.1', 'ISO-8859-1', NULL, NULL),
                1 => new ImapBodyPart(0, 4, 'HTML', '1.2', 'ISO-8859-1', NULL, NULL),
            ],
            1 => [
                0 => new ImapBodyPart(5, 3, 'PNG', '2', NULL, 'wrench.png', '<wrench>'),
            ],
            2 => [
                0 => new ImapBodyPart(5, 3, 'PNG', '3', NULL, 'google_logo.png', '<google_logo>'),
            ],
        ];
    }

}