<?php

namespace App\Components\Utils;

class TokenGenerator {

  public static function generateToken(){
    return rtrim(strtr(base64_encode(self::getRandomNumber()), '+/', '-_'), '=');
  }
  private static function getRandomNumber()
  {
    return hash('sha256', uniqid(mt_rand(), true), true);
  }
}