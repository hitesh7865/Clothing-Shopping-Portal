<?php

namespace App\Services;

class MailClient
{
    public static $KEYS = [ "GMAIL" => "gmail"];
    public static function getClient($imapAddress =null, $username = null, $password = null, $readFolder = "INBOX", $splitChainsWith = "--Reply above this line--", $path = null, $connectionType =null)
    {
        switch ($connectionType) {
          case MailClient::$KEYS['GMAIL']:
            return new GmailClient($imapAddress, $username, $password, $readFolder, $splitChainsWith, $path);
            break;
          default:
            return new GmailClient($imapAddress, $username, $password, $readFolder, $splitChainsWith, $path);
            break;
      }
    }
}
