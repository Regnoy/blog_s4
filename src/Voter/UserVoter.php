<?php

namespace App\Voter;

use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class UserVoter extends Voter
{

  const IS_ADMIN = 'user.is_admin';

  protected function supports($attribute, $subject)
  {

    if (!in_array($attribute, array( self::IS_ADMIN))) {
      return false;
    }

    if (!$subject instanceof User) {
      return false;
    }
    return true;
  }

  protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
  {
    $user = $token->getUser();

    if (!$user instanceof User) {
      // the user must be logged in; if not, deny access
      return false;
    }

    switch ($attribute){
      case self::IS_ADMIN:
        return $this->isAdmin($user);
        break;
    }

    throw new \LogicException('This code should not be reached!');
  }

  public function isAdmin(User $user){

    return in_array('ROLE_ADMIN', $user->getRoles());
  }
}