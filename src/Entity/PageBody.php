<?php


namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
/**
 * Class Page
 * @package PageBundle\Entity
 * @ORM\Entity
 * @ORM\Table(name="page_body")
 */

class PageBody
{
  /**
   * @ORM\Column(type="integer")
   * @ORM\Id
   * @ORM\GeneratedValue(strategy="AUTO")
   */
  private $id;

  /**
   * @ORM\ManyToOne(targetEntity="PageData", inversedBy="body")
   * @ORM\JoinColumn(name="entity_id", referencedColumnName="id")
   */
  private $entity;
  /**
   * @ORM\Column(type="text")
   */
  private $body;

  /**
   * @ORM\Column(type="string", length=191)
   */
  private $summary;

  /**
   * @return mixed
   */
  public function getId()
  {
    return $this->id;
  }



  /**
   * @return mixed
   */
  public function getEntity()
  {
    return $this->entity;
  }

  /**
   * @param mixed $entity
   */
  public function setEntity($entity): void
  {
    $this->entity = $entity;
  }

  /**
   * @return mixed
   */
  public function getBody()
  {
    return $this->body;
  }

  /**
   * @param mixed $body
   */
  public function setBody($body): void
  {
    $this->body = $body;
  }


  /**
   * @return mixed
   */
  public function getSummary()
  {
    return $this->summary;
  }

  /**
   * @param mixed $summary
   */
  public function setSummary($summary): void
  {
    $this->summary = $summary;
  }

}