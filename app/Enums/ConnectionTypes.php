<?php

namespace App\Enums;

class ConnectionTypes extends Enum
{
  const GMAIL = "gmail";
  const AOL = "aol";
  const OUTLOOK = "outlook";
  const ZOHO = "zoho";
  const MAIL = "mail";
  const YAHOO = "yahoo";
  const LIVE = "live";
  const CUSTOM = "custom";
  const LIST_ALL = [
    [
      'id' => "gmail",
      'connection' => "{imap.gmail.com:993/imap/ssl}",
      'value' => "Gmail"
    ],
    // [
    //   'id' => "aol",
    //   'connection' => "{imap.aol.com:993/imap/ssl}",
    //   'value' => 'AOL'
    // ],
    [
      'id' => "outlook",
      'connection' => "{outlook.office365.com:993/imap/ssl}",
      'value' => 'Outlook'
    ],
    // [
    //   'id' => "zoho",
    //   'connection' => "{imap.zoho.com:993/imap/ssl}",
    //   'value' => 'Zoho'
    // ],
    // [
    //   'id' => "mail",
    //   'connection' => "{imap.mail.com:993/imap/ssl}",
    //   'value' => 'Mail.com'
    // ],
    [
      'id' => "yahoo",
      'connection' => "{imap.mail.yahoo.com:993/imap/ssl}",
      'value' => 'Yahoo'
    ],
    // [
    //   'id' => "live",
    //   'connection' => "{imap-mail.outlook.com:993/imap/ssl}",
    //   'value' => 'Live'
    // ],
    [
      'id' => "custom",
      'connection' => "",
      'value' => 'Custom'
    ]

  ];
}
