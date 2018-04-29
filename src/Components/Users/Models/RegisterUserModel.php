<?php

namespace App\Components\Users\Models;

use CoreBundle\Core\Core;
use Symfony\Component\Validator\Constraints as Assert;
use App\Entity\User;
use App\Entity\UserAccount;

class RegisterUserModel {

  public $firstName;

  public $lastName;

  public $email;

  public $password;

  public $birthday;

  public $region;

  /**
   * @return mixed
   */
  public function getFirstName() {
    return $this->firstName;
  }

  /**
   * @param mixed $firstName
   */
  public function setFirstName($firstName) {
    $this->firstName = $firstName;
  }

  /**
   * @return mixed
   */
  public function getLastName() {
    return $this->lastName;
  }

  /**
   * @param mixed $lastName
   */
  public function setLastName($lastName) {
    $this->lastName = $lastName;
  }

  /**
   * @return mixed
   */
  public function getEmail() {
    return $this->email;
  }

  /**
   * @param mixed $email
   */
  public function setEmail($email) {
    $this->email = $email;
  }

  /**
   * @return mixed
   */
  public function getPassword() {
    return $this->password;
  }

  /**
   * @param mixed $password
   */
  public function setPassword($password) {
    $this->password = $password;
  }

  /**
   * @return mixed
   */
  public function getBirthday() {
    return $this->birthday;
  }

  /**
   * @param mixed $birthday
   */
  public function setBirthday($birthday) {
    $this->birthday = $birthday;
  }

  /**
   * @return mixed
   */
  public function getRegion() {
    return $this->region;
  }

  /**
   * @param mixed $region
   */
  public function setRegion($region) {
    $this->region = $region;
  }

  public function getUserHandler(){
    $user = new User();
    $account = new UserAccount();
    $account->setFirstName($this->firstName);
    $account->setLastName($this->lastName);
    $account->setBirthday($this->birthday);
    $account->setRegion($this->region);
    $user->setEmail($this->email);
    $user->setAccount($account);
    $encoder = Core::service('security.password_encoder');
    $password = $encoder->encodePassword($user, $this->password);
    $user->setPassword($password);
    return $user;
  }



}