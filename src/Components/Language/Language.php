<?php
/**
 * Created by PhpStorm.
 * User: pavel
 * Date: 8/29/2018
 * Time: 8:36 AM
 */

namespace App\Components\Language;


class Language
{

  private $language;

  private $name;

  /**
   * @return mixed
   */
  public function getLanguage()
  {
    return $this->language;
  }

  /**
   * @param mixed $langauge
   */
  public function setLanguage($language): void
  {
    $this->language = $language;
  }

  /**
   * @return mixed
   */
  public function getName()
  {
    return $this->name;
  }

  /**
   * @param mixed $name
   */
  public function setName($name): void
  {
    $this->name = $name;
  }


}