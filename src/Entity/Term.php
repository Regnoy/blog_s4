<?php


namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Page
 * @package PageBundle\Entity
 * @ORM\Entity
 * @ORM\Table(name="term")
 */
class Term {
  /**
   * @ORM\Column(type="integer")
   * @ORM\Id
   * @ORM\GeneratedValue(strategy="AUTO")
   */
  private $id;

  /**
   * @ORM\Column(type="string", length=191)
   */
  private $machineName;

  /**
   * @ORM\Column(type="datetime")
   */
  private $created;


  /**
   * @ORM\OneToMany(targetEntity="PageData", mappedBy="category")
   */
  private $entities;



  /**
   * Get id
   *
   * @return integer
   */
  public function getId()
  {
    return $this->id;
  }

  /**
   * @return mixed
   */
  public function getMachineName()
  {
    return $this->machineName;
  }

  /**
   * @param mixed $machineName
   */
  public function setMachineName($machineName): void
  {
    $this->machineName = $machineName;
  }



  /**
   * Set created
   *
   * @param \DateTime $created
   *
   * @return Term
   */
  public function setCreated($created)
  {
    $this->created = $created;

    return $this;
  }

  /**
   * Get created
   *
   * @return \DateTime
   */
  public function getCreated()
  {
    return $this->created;
  }
  /**
   * Constructor
   */
  public function __construct()
  {
    $this->entities = new ArrayCollection();
    $this->created = new \DateTime();
  }

  /**
   * Add page
   *
   * @param \App\Entity\PageData $page
   *
   * @return Term
   */
  public function addEntity(\App\Entity\PageData $pageData)
  {
    $this->entities[] = $pageData;

    return $this;
  }

  /**
   * Remove page
   *
   * @param \App\Entity\PageData $page
   */
  public function removeEntity(\App\Entity\PageData $pageData)
  {
    $this->entities->removeElement($pageData);
  }

  /**
   * Get pages
   *
   * @return \Doctrine\Common\Collections\Collection
   */
  public function getPages()
  {
    return $this->entities;//$this->pages->toArray();
  }
  public function __toString() {
    return $this->machineName;
  }

  public static function termList(){
    return ['term_one', 'term_two' , 'term_three'];
  }
}
