<?php


namespace App\Entity;


use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class User
 * @package App\Entity
 * @ORM\Entity(repositoryClass="\App\Repository\UserRepository")
 * @ORM\Table(name="user")
 */
class User implements \Serializable ,UserInterface {

  /**
   * @ORM\Column(type="integer")
   * @ORM\Id
   * @ORM\GeneratedValue(strategy="AUTO")
   */
  private $id;

  /**
   * @ORM\Column(type="string", unique=true)
   */
  private $username;

  /**
   * @ORM\Column(type="string", unique=true)
   */
  private $email;

  /**
   * @ORM\Column(type="string")
   */
  private $password;
//  /**
//   * @ORM\Column(type="string")
//   */
//  private $salt;

  /**
   * @ORM\Column(type="datetime")
   */
  private $created;
  /**
   * @ORM\Column(type="datetime")
   */
  private $updated;
  /**
   * @ORM\Column(type="smallint")
   */
  private $status;

  /**
   * @var \Doctrine\Common\Collections\ArrayCollection
   * @ORM\ManyToMany(targetEntity="Role", inversedBy="users", cascade={"persist", "remove"})
   * @ORM\JoinTable(name="users_roles")
   */
  private $roles;

  /**
   * @ORM\OneToOne(targetEntity="UserAccount", mappedBy="user", cascade={"persist", "remove"})
   */
  private $account;

  public function __construct() {
    $this->roles = new ArrayCollection();
//    $this->salt = md5( uniqid(null, TRUE) );

    $this->username = md5( uniqid(null, TRUE) );
    $this->created = new \DateTime();
    $this->updated = new \DateTime();
    $this->status = 1;
  }


  public function serialize()
  {
    return serialize(array(
      $this->id,
      $this->username,
      $this->password
    ));
  }

  public function unserialize($serialized)
  {
    list($this->id, $this->username, $this->password) = unserialize($serialized, ['allowed_classes' => false]);
  }

  public function getRoles() {
    // return $this->roles->toArray();
    $roles = [];
    /** @var Role $role */
    foreach ($this->roles as $role){
      $roles[] = $role->getRole();
    }
    return $roles;
  }
  public function getPassword() {
    return $this->password;
  }
  public function getSalt() {
    return null;
  }
  public function getUsername() {
    return $this->username;
  }
  public function eraseCredentials() {

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
   * Set username
   *
   * @param string $username
   *
   * @return User
   */
  public function setUsername($username)
  {
    $this->username = $username;

    return $this;
  }

  /**
   * Set email
   *
   * @param string $email
   *
   * @return User
   */
  public function setEmail($email)
  {
    $this->email = $email;

    return $this;
  }

  /**
   * Get email
   *
   * @return string
   */
  public function getEmail()
  {
    return $this->email;
  }

  /**
   * Set password
   *
   * @param string $password
   *
   * @return User
   */
  public function setPassword($password)
  {
    $this->password = $password;

    return $this;
  }

  /**
   * Set salt
   *
   * @param string $salt
   *
   * @return User
   */
  public function setSalt($salt)
  {
    $this->salt = $salt;

    return $this;
  }

  /**
   * Set created
   *
   * @param \DateTime $created
   *
   * @return User
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
   * Set updated
   *
   * @param \DateTime $updated
   *
   * @return User
   */
  public function setUpdated($updated)
  {
    $this->updated = $updated;

    return $this;
  }

  /**
   * Get updated
   *
   * @return \DateTime
   */
  public function getUpdated()
  {
    return $this->updated;
  }

  /**
   * Set status
   *
   * @param integer $status
   *
   * @return User
   */
  public function setStatus($status)
  {
    $this->status = $status;

    return $this;
  }

  /**
   * Get status
   *
   * @return integer
   */
  public function getStatus()
  {
    return $this->status;
  }

  /**
   * Add role
   *
   * @param \App\Entity\Role $role
   *
   * @return User
   */
  public function addRole(\App\Entity\Role $role)
  {
    $this->roles[] = $role;

    return $this;
  }

  /**
   * Remove role
   *
   * @param \App\Entity\Role $role
   */
  public function removeRole(\App\Entity\Role $role)
  {
    $this->roles->removeElement($role);
  }

  /**
   * Set account
   *
   * @param \App\Entity\UserAccount $account
   *
   * @return User
   */
  public function setAccount(\App\Entity\UserAccount $account = null)
  {
    $this->account = $account;
    $account->setUser($this);

    return $this;
  }

  /**
   * Get account
   *
   * @return \App\Entity\UserAccount
   */
  public function getAccount()
  {
    return $this->account;
  }

  public function getFullname(){
    $fullname = $this->getAccount()->getFirstName();
    $lastName = $this->getAccount()->getLastName();
    if($lastName){
      $fullname = $fullname." ".$lastName;
    }
    return $fullname;
  }
}
