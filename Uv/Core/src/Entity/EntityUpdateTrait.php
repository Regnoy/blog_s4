<?php


namespace Uv\Core\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Trait EntityUpdateTrait
 * @package Uv\Core\Entity
 * @ORM\HasLifecycleCallbacks()
 */
trait EntityUpdateTrait
{
  /**
   * @ORM\Column(type="datetime")
   */
  private $updated;


  /**
   * @return \DateTime
   */
  public function getUpdated()
  {
    return $this->updated;
  }

  /**
   * @param \DateTime $updated
   * @return $this
   */
  public function setUpdated($updated)
  {
    $this->updated = $updated;
    return $this;
  }

  /**
   * @ORM\PreUpdate
   * @ORM\PrePersist
   */
  public function changeUpdateDateTime(){
    $this->updated = new \DateTime();
  }

}