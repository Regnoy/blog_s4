<?php

namespace App\Entity;

use App\Components\Language\CurrentLanguage;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Page
 * @package PageBundle\Entity
 * @ORM\Entity(repositoryClass="App\Repository\PageRepository")
 * @ORM\Table(name="page")
 * @ORM\HasLifecycleCallbacks()
 */
class Page {

  /**
   * @ORM\Column(type="integer")
   * @ORM\Id
   * @ORM\GeneratedValue(strategy="AUTO")
   */
  private $id;
  /**
   * ORIGINAL LANGUAGE
   * @ORM\Column(type="string", length=191)
   */
  private $language;

  /**
   * @ORM\OneToMany(targetEntity="PageData", mappedBy="page", cascade={"persist", "remove"})
   */
  private $data;

  /**
   * @ORM\ManyToOne(targetEntity="User")
   * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
   */
  private $user;

  /**
   * @ORM\OneToMany(targetEntity="Comment", mappedBy="page", cascade={"persist", "remove"})
   */
  private $comments;

  public function __construct()
  {
    $this->data = new ArrayCollection();
    $this->comments = new ArrayCollection();
  }

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
  public function getUser()
  {
    return $this->user;
  }


  public function setUser(User $user)
  {
    $this->user = $user;
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

  public function addData( PageData $data ){
    $data->setPage($this);
    $this->data->add($data);
  }

  public function removeData( PageData $data ){
    $this->data->remove($data);
  }

  public function getData(){
    return $this->data;
  }
  public function getEntities(){
    $entities = [];
    foreach ($this->data->toArray() as $data){
      $entities[$data->getLanguage()] = $data;
    }
    return $entities;
  }

  /**
   * @param null $language
   * @return PageData
   */
  public function getEntity( $language = null ){
    if(is_null($language)){
      $language = CurrentLanguage::$language;
    }

    $arr = $this->data->filter(function($entity) use ($language){
      return $entity->getLanguage() == $language;
    });
    $entity = $arr->first();
    if(!$entity){
      $entity = new PageData();
      $entity->setLanguage($language);
      $this->addData($entity);
    }
    return $entity;
  }
  /**
   * @ORM\PrePersist
   */
  public function setPrePersist()
  {
    $this->language = CurrentLanguage::$language;
  }

  public function addComment(Comment $comment) : void{
    $comment->setPage($this);
    $this->comments[] = $comment;
  }

  public function removeComment(Comment $comment) : void{
    $this->comments->remove($comment);
  }

  /**
   * @return ArrayCollection
   */
  public function getComments() : ArrayCollection
  {
    return $this->comments;
  }

  public function getCommentPublished(){
    return $this->comments->filter(function($commnet){
      return $commnet->getMarking() == 'publish';
    });
  }

  public function getCommentUnpublished(){
    return $this->comments->filter(function($commnet){
      return $commnet->getMarking() == 'unpublish';
    });
  }


}
