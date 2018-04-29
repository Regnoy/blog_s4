<?php


namespace App\DataFixtures\ORM;


use Doctrine\Bundle\FixturesBundle\Fixture;


use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Role;

class RoleFixtures extends Fixture {
  
  public function load(ObjectManager $manager) {
    $roleRepo = $manager->getRepository(Role::class);
    $role = $roleRepo->findByRole('ROLE_USER');
    if(!$role){
      $role = new Role();
      $role->setName("ROLE USER");
      $role->setRole("ROLE_USER");
      $manager->persist($role);
      $manager->flush();
    }
  }

}