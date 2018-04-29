<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
/**
 * Class User
 * @package UserBundle\Entity
 * @ORM\Entity
 * @ORM\Table(name="user_account")
 */
class UserAccount {
  /**
   * @ORM\Column(type="integer")
   * @ORM\Id
   * @ORM\GeneratedValue(strategy="AUTO")
   */
  private $id;
  
  /**
   * @ORM\OneToOne(targetEntity="User", inversedBy="account")
   * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
   */
  private $user;
  /**
   * @ORM\Column(type="string")
   */
  private $firstName;
  /**
   * @ORM\Column(type="string")
   */
  private $lastName;
  /**
   * @ORM\Column(type="datetime")
   */
  private $birthday;
  /**
   * @ORM\Column(type="string", nullable=true)
   */
  private $region;

  /**
   * @ORM\Column(type="string", nullable=true)
   */
  private $tokenRecover;
  
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
   * Set firstName
   *
   * @param string $firstName
   *
   * @return UserAccount
   */
  public function setFirstName($firstName)
  {
    $this->firstName = $firstName;
    
    return $this;
  }
  
  /**
   * Get firstName
   *
   * @return string
   */
  public function getFirstName()
  {
    return $this->firstName;
  }
  
  /**
   * Set lastName
   *
   * @param string $lastName
   *
   * @return UserAccount
   */
  public function setLastName($lastName)
  {
    $this->lastName = $lastName;
    
    return $this;
  }
  
  /**
   * Get lastName
   *
   * @return string
   */
  public function getLastName()
  {
    return $this->lastName;
  }
  
  /**
   * Set birthday
   *
   * @param \DateTime $birthday
   *
   * @return UserAccount
   */
  public function setBirthday($birthday)
  {
    $this->birthday = $birthday;
    
    return $this;
  }
  
  /**
   * Get birthday
   *
   * @return \DateTime
   */
  public function getBirthday()
  {
    return $this->birthday;
  }
  
  /**
   * Set region
   *
   * @param string $region
   *
   * @return UserAccount
   */
  public function setRegion($region)
  {
    $this->region = $region;
    
    return $this;
  }
  
  /**
   * Get region
   *
   * @return string
   */
  public function getRegion()
  {
    return $this->region;
  }
  
  /**
   * Set user
   *
   * @param \App\Entity\User $user
   *
   * @return UserAccount
   */
  public function setUser(\App\Entity\User $user = null)
  {
    $this->user = $user;
    
    return $this;
  }
  
  /**
   * Get user
   *
   * @return \App\Entity\User
   */
  public function getUser()
  {
    return $this->user;
  }

    /**
     * Set tokenRecover
     *
     * @param string $tokenRecover
     *
     * @return UserAccount
     */
    public function setTokenRecover($tokenRecover)
    {
        $this->tokenRecover = $tokenRecover;
    
        return $this;
    }

    /**
     * Get tokenRecover
     *
     * @return string
     */
    public function getTokenRecover()
    {
        return $this->tokenRecover;
    }
}
