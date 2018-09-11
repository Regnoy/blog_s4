<?php

namespace App\Components\Users\Events;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Workflow\Event\GuardEvent;

class CommentWorkflow implements EventSubscriberInterface
{
  private $token;

  public function __construct( TokenStorageInterface $tokenStorage )
  {
    $this->token = $tokenStorage;
  }

  public function onGuardPublished(GuardEvent $event){
    $user = $this->getUser();
    if(!$user){
      $event->setBlocked(true);
    }
    if(!in_array('ROLE_ADMIN', $user->getRoles())){
      $event->setBlocked(true);
    }
  }

  public function getUser(){
    if (null === $token = $this->token->getToken()) {
      return null;
    }

    if (!is_object($user = $token->getUser())) {
      // e.g. anonymous authentication
      return null;
    }

    return $user;
  }
  public static function getSubscribedEvents()
  {
    return [
      'workflow.comment.guard.published' => 'onGuardPublished'
    ];
  }
}