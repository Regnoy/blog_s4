<?php


namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use PageBundle\Entity\Page;

/**
 * Class Page
 * @package PageBundle\Entity
 * @ORM\Entity(repositoryClass="App\Repository\CommentRepository")
 * @ORM\Table(name="comment")
 * @ORM\HasLifecycleCallbacks()
 */
class Comment {

  /**
   * @ORM\Column(type="integer")
   * @ORM\Id
   * @ORM\GeneratedValue(strategy="AUTO")
   */
  private $id;
  /**
   * @ORM\Column(type="text")
   */
  private $comment;


  /**
   * @ORM\ManyToOne(targetEntity="Page", inversedBy="comments")
   * @ORM\JoinColumn(name="page_id", referencedColumnName="id")
   */
  private $page;

  /**
   * @ORM\ManyToOne(targetEntity="User")
   * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
   */

  private $user;

  /**
   * @ORM\Column(type="datetime")
   */
  private $created;

  /**
   * @ORM\Column(type="string", length=191, nullable=true)
   */
  private $marking;

  public function  __construct(){
    $this->created = new \DateTime();
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
     * Set comment
     *
     * @param string $comment
     *
     * @return Comment
     */
    public function setComment($comment)
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * Get comment
     *
     * @return string
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     *
     * @return Comment
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
     * Add page
     *
     * @param \App\Entity\Page $page
     *
     * @return Comment
     */
    public function setPage(\App\Entity\Page $page)
    {
        $this->page = $page;

        return $this;
    }


    /**
     * Get pages
     *
     * @return Page
     */
    public function getPage()
    {
        return $this->page;
    }

  /**
   * @return mixed
   */
  public function getUser()
  {
    return $this->user;
  }

  /**
   * @param  $user
   */
  public function setUser(User $user)
  {

    $this->user = $user;
  }
  /**
   * @ORM\PrePersist
   */
  public function setPrePersistCommentMarking()
  {
    if(!$this->marking)
      $this->marking = 'unpublish';
  }
}
