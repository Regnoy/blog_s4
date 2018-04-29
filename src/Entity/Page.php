<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;


/**
 * Class Page
 * @package PageBundle\Entity
 * @ORM\Entity(repositoryClass="App\Repository\PageRepository")
 * @ORM\Table(name="page")
 */
class Page {

  /**
   * @ORM\Column(type="integer")
   * @ORM\Id
   * @ORM\GeneratedValue(strategy="AUTO")
   */
  private $id;

  /**
   * @ORM\Column(type="string", length=255)
   */
  private $title;

  /**
   * @ORM\Column(type="text")
   */
  private $body;

  /**
   * @ORM\ManyToOne(targetEntity="\App\Entity\Term", inversedBy="pages")
   * @ORM\JoinColumn(name="term_id", referencedColumnName="id")
   */
  private $category;

  /**
   * @ORM\Column(type="datetime")
   */
  private $created;

  /**
   * @ORM\OneToMany(targetEntity="\App\Entity\Comment", mappedBy="page", cascade={"persist", "remove"})
   * @ORM\OrderBy({"id" = "DESC"})
   */
  private $comments;

  /**
   * @ORM\ManyToOne(targetEntity="\App\Entity\User")
   * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
   */
  private $user;

  public function __construct() {
    $this->created = new \DateTime();
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
   * Set title
   *
   * @param string $title
   *
   * @return Page
   */
  public function setTitle($title)
  {
    $this->title = $title;

    return $this;
  }

  /**
   * Get title
   *
   * @return string
   */
  public function getTitle()
  {
    return $this->title;
  }

  /**
   * Set body
   *
   * @param string $body
   *
   * @return Page
   */
  public function setBody($body)
  {
    $this->body = $body;

    return $this;
  }

  /**
   * Get body
   *
   * @return string
   */
  public function getBody()
  {
    return $this->body;
  }

  /**
   * Set created
   *
   * @param \DateTime $created
   *
   * @return Page
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
   * Set category
   *
   * @param \App\Entity\Term $category
   *
   * @return Page
   */
  public function setCategory(Term $category = null)
  {
    $this->category = $category;

    return $this;
  }

  /**
   * Get category
   *
   * @return Term
   */
  public function getCategory()
  {
    return $this->category;
  }

  /**
   * Add comment
   *
   * @param Comment $comment
   *
   * @return Page
   */
  public function addComment(Comment $comment)
  {
    $this->comments[] = $comment;
    return $this;
  }

  /**
   * Remove comment
   *
   * @param \App\Entity\Comment $comment
   */
  public function removeComment(\App\Entity\Comment $comment)
  {
    $this->comments->removeElement($comment);
  }

  /**
   * Get comments
   *
   * @return \Doctrine\Common\Collections\Collection
   */
  public function getComments()
  {
    return $this->comments;
  }
  public function setId(){
    //
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

}
