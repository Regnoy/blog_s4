<?php


namespace App\Entity;

use App\Components\Language\CurrentLanguage;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Uv\Core\Entity\EntityIdTrait;
use Uv\Core\Entity\EntityUpdateTrait;


/**
 * Class Page
 * @package PageBundle\Entity
 * @ORM\Entity
 * @ORM\Table(name="page_data")
 * @ORM\HasLifecycleCallbacks()
 */
class PageData
{

  use EntityIdTrait;
  use EntityUpdateTrait;

  /**
   * @ORM\ManyToOne(targetEntity="Page", inversedBy="data")
   * @ORM\JoinColumn(name="page_id", referencedColumnName="id")
   */
  private $page;

  /**
   * @ORM\Column(type="string", length=191)
   */
  private $title;

  /**
   * @ORM\Column(type="string", length=191)
   */
  private $language;
  /**
   * @ORM\Column(type="datetime")
   */
  private $created;

  /**
   * @ORM\Column(type="string", length=191, nullable=true)
   */
  private $marking;

  /**
   * @ORM\OneToMany(targetEntity="PageBody", mappedBy="entity", cascade={"persist", "remove"})
   */
  private $body;

  /**
   * @ORM\ManyToOne(targetEntity="Term")
   * @ORM\JoinColumn(name="term_id", referencedColumnName="id")
   */

  private $category;

  public function __construct()
  {
    $this->body = new ArrayCollection();
  }


  /**
   * @return Page
   */
  public function getPage()
  {
    return $this->page;
  }

  /**
   * @param mixed $page
   */
  public function setPage($page): void
  {
    $this->page = $page;
  }

  /**
   * @return mixed
   */
  public function getTitle()
  {
    return $this->title;
  }

  /**
   * @param mixed $title
   */
  public function setTitle($title): void
  {
    $this->title = $title;
  }

  /**
   * @return mixed
   */
  public function getLanguage()
  {
    return $this->language;
  }

  /**
   * @param mixed $language
   */
  public function setLanguage($language): void
  {
    $this->language = $language;
  }

  /**
   * @return mixed
   */
  public function getCreated()
  {
    return $this->created;
  }

  /**
   * @param mixed $created
   */
  public function setCreated($created): void
  {
    $this->created = $created;
  }

  /**
   * @return mixed
   */
  public function getMarking()
  {
    return $this->marking;
  }

  /**
   * @param mixed $marking
   */
  public function setMarking($marking): void
  {
    $this->marking = $marking;
  }

  /**
   * @return mixed
   */
  public function getBody()
  {
    return $this->body;
  }


  /**
   * @param PageBody $body
   */
  public function addBody(PageBody $body): void
  {
    $body->setEntity($this);
    $this->body->add($body);
  }

  /**
   * @return PageBody
   */
  public function getFieldBody(){
    return $this->body->first();
  }

  /**
   * @return Term
   */
  public function getCategory()
  {
    return $this->category;
  }
  public function setCategory(Term $term){
    $this->category = $term;
    return $this;
  }


  /**
   * @ORM\PrePersist
   */
  public function setPrePersist()
  {
    if(is_null($this->language)){
      $this->language = $this->getPage()->getLanguage();
    }
    $this->created = new \DateTime();
    $this->marking = 'draft';
  }


}