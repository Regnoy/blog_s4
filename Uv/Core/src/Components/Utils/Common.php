<?php

namespace Uv\Core\Components\Utils;

class Common {
  public static function convertArrayToSubArrays(array $convert_array, $value){
    $arr = array();
    $ref = &$arr;
    $ttl = count($convert_array);
    foreach ($convert_array as $k => $key) {
      $def = [];
      if($k == ($ttl-1))
        $def = $value;
      $ref[$key] = $def;
      $ref = &$ref[$key];
    }
    return $arr;
  }

  public static function escape($text) {
    return htmlspecialchars($text, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
  }
  public static function decodeEntities($text) {
    return html_entity_decode($text, ENT_QUOTES, 'UTF-8');
  }
  public static function multiArrayToArrayByKey($multiArray, $key){
    $arr = [];
    foreach ($multiArray as $array){
      if(isset($array[$key])){
        $id = $array[$key];
        $arr[$id] = $id;
      }
    }
    return $arr;
  }
  public static function removeEmptyTagsRecursive ($str, $repto = NULL)
  {
    //** Return if string not given or empty.
    if (!is_string ($str)
      || trim ($str) == '')
      return $str;

    //** Recursive empty HTML tags.
    return preg_replace (
    //** Pattern written by Junaid Atari.
      '~<(\w+)[^>]*>(?>[\p{Z}\p{C}]|<br\b[^>]*>|&(?:(?:nb|thin|zwnb|e[nm])sp|zwnj|#xfeff|#xa0|#160|#65279);|(?R))*</\1>~iu',
      //** Replace with nothing if string empty.
      !is_string ($repto) ? '' : $repto,
      //** Source string
      $str
    );
  }

  public static function substr($text, $start, $length = null, $endText = null) {

    $strlen = strlen($text);
    // Find the starting byte offset.
    $bytes = 0;
    if ($start > 0) {
      // Count all the characters except continuation bytes from the start
      // until we have found $start characters or the end of the string.
      $bytes = -1;
      $chars = -1;
      while ($bytes < $strlen - 1 && $chars < $start) {
        $bytes++;
        $c = ord($text[$bytes]);
        if ($c < 0x80 || $c >= 0xC0) {
          $chars++;
        }
      }
    } elseif ($start < 0) {
      // Count all the characters except continuation bytes from the end
      // until we have found abs($start) characters.
      $start = abs($start);
      $bytes = $strlen;
      $chars = 0;
      while ($bytes > 0 && $chars < $start) {
        $bytes--;
        $c = ord($text[$bytes]);
        if ($c < 0x80 || $c >= 0xC0) {
          $chars++;
        }
      }
    }
    $istart = $bytes;

    // Find the ending byte offset.
    if ($length === NULL) {
      $iend = $strlen;
    } elseif ($length > 0) {
      // Count all the characters except continuation bytes from the starting
      // index until we have found $length characters or reached the end of
      // the string, then backtrace one byte.
      $iend = $istart - 1;
      $chars = -1;
      $last_real = FALSE;
      while ($iend < $strlen - 1 && $chars < $length) {
        $iend++;
        $c = ord($text[$iend]);
        $last_real = FALSE;
        if ($c < 0x80 || $c >= 0xC0) {
          $chars++;
          $last_real = TRUE;
        }
      }
      // Backtrace one byte if the last character we found was a real
      // character and we don't need it.
      if ($last_real && $chars >= $length) {
        $iend--;
      }
    }
    elseif ($length < 0) {
      // Count all the characters except continuation bytes from the end
      // until we have found abs($start) characters, then backtrace one byte.
      $length = abs($length);
      $iend = $strlen;
      $chars = 0;
      while ($iend > 0 && $chars < $length) {
        $iend--;
        $c = ord($text[$iend]);
        if ($c < 0x80 || $c >= 0xC0) {
          $chars++;
        }
      }
      // Backtrace one byte if we are not at the beginning of the string.
      if ($iend > 0) {
        $iend--;
      }
    }
    else {
      // $length == 0, return an empty string.
      return '';
    }

    $output = substr($text, $istart, max(0, $iend - $istart + 1));

    return $strlen > $length ? $output.$endText : $output;
  }
}