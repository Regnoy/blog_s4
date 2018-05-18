<?php

namespace App\DataFixtures\ORM;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Role;
use App\Entity\User;
use App\Entity\UserAccount;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;

class UserFixtures extends Fixture implements DependentFixtureInterface {

  private $container;

  public function __construct( ContainerInterface $container)
  {
    $this->container = $container;
  }

  public function load(ObjectManager $manager) {

    $roleRepo = $manager->getRepository(Role::class);
    $role = $roleRepo->findOneByRole('ROLE_USER');
    $roleAdmin = $roleRepo->findOneByRole('ROLE_ADMIN');
    if(!$role)
      return;

    $encoder = $this->container->get('security.password_encoder');
    $user = new User();
    $password = $encoder->encodePassword($user, '123456');
    $user->setPassword($password);
    $user->addRole($role);
    $user->addRole($roleAdmin);
    $user->setEmail('info@utilvideo.com');

    $userAccount = new UserAccount();
    $userAccount->setFirstName('John')->setLastName('Doe');
    $userAccount->setBirthday( new \DateTime() );
    $manager->persist($user);
    $manager->flush();
    $userAccount->setUser($user);

    $manager->persist($userAccount);


    $user = new User();
    $password = $encoder->encodePassword($user, '123456');
    $user->setPassword($password);
    $user->addRole($role);
    $user->setEmail('user@utilvideo.com');

    $userAccount = new UserAccount();
    $userAccount->setFirstName('User')->setLastName('Die');
    $userAccount->setBirthday( new \DateTime() );
    $manager->persist($user);
    $manager->flush();
    $userAccount->setUser($user);

    $manager->persist($userAccount);
    $manager->flush();



  }

  public function getDependencies()
  {
    return [
      RoleFixtures::class
    ];
  }

}