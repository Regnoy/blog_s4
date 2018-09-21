<?php

namespace Uv\Core\Entity;

use Doctrine\ORM\Mapping as ORM;

trait EntityIdTrait
{
  /**
   * @ORM\Column(type="integer")
   * @ORM\Id
   * @ORM\GeneratedValue(strategy="AUTO")
   */
  private $id;

  /**
   * @return integer
   */
  public function id(){
    return $this->id;
  }

  /**
   * @return integer
   */
  public function getId()
  {
    return $this->id;
  }


}