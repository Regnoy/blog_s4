<?php


namespace App\Components\Language;


class LanguageManager
{
  public function getLanguages(){
    $list = [];
    $language = new Language();
    $language->setName('English');
    $language->setLanguage('en');
    $list[$language->getLanguage()] = $language;
    $language = new Language();
    $language->setName('Russian');
    $language->setLanguage('ru');
    $list[$language->getLanguage()] = $language;
    return $list;
  }
}