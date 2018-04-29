<?php


namespace App\Components\Users\Provider;



use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;


class UserProvider implements UserProviderInterface {

  private $em;

  public function __construct( EntityManagerInterface $entity_manager ) {
    $this->em = $entity_manager;
  }

  public function loadUserByUsername($username) {
    $user = $this->em->getRepository(User::class)->loadUserByUsername($username);
    if($user)
      return $user;

    throw new UsernameNotFoundException(
      sprintf('Username "%s" does not exist.', $username)
    );
  }

  public function refreshUser(UserInterface $user) {
    return $this->loadUserByUsername($user->getUsername());
  }

  public function supportsClass($class) {
    return User::class === $class;
  }
//HWIOauthBundle
//  public function loadUserByOAuthUserResponse(UserResponseInterface $response){
//
//  }
}