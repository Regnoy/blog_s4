<?php


namespace Uv\Core\Components\Utils;

class Unicode {
  public static function validateUtf8($text) {
    if (strlen($text) == 0) {
      return TRUE;
    }
    // With the PCRE_UTF8 modifier 'u', preg_match() fails silently on strings
    // containing invalid UTF-8 byte sequences. It does not reject character
    // codes above U+10FFFF (represented by 4 or more octets), though.
    return (preg_match('/^./us', $text) == 1);
  }
}