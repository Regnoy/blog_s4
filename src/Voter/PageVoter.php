<?php

namespace App\Voter;

use App\Entity\Page;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;


class PageVoter extends Voter
{
  const EDIT = 'EDIT';
  private $decisionManager;

  public function __construct(AccessDecisionManagerInterface $decisionManager)
  {
    $this->decisionManager = $decisionManager;
  }

  protected function supports($attribute, $subject)
  {

    if (!in_array($attribute, array( self::EDIT))) {
      return false;
    }

    if (!$subject instanceof Page) {
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
      case self::EDIT:
        return $this->canEdit($subject, $user, $token);
        break;
    }

    throw new \LogicException('This code should not be reached!');
  }
  public function canEdit(Page $page, User $user, TokenInterface $token){

    if ($this->decisionManager->decide($token, array('ROLE_ADMIN'))) {
      return true;
    }

    return $page->getUser()->getId() == $user->getId();
  }
}

