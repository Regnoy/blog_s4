<?php
/**
 * Created by PhpStorm.
 * User: pavel
 * Date: 10/9/2017
 * Time: 9:46 AM
 */

namespace App\DataFixtures\ORM;


use App\Entity\Page;
use App\Entity\Term;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;


class PageFixtures extends Fixture implements DependentFixtureInterface {

  public function load(ObjectManager $manager) {
    $termRepo = $manager->getRepository(Term::class);
    $user = $manager->getRepository(User::class)->findOneByEmail('info@utilvideo.com');
    $terms = $termRepo->findAll('Term');

    foreach ($terms as $term){
      $page = new Page();
      $page->setTitle('Page '.$term->getId());
      $page->setBody('Body Page'. $term->getId());
      $page->setCategory($term);
      $page->setUser($user);
      $page->setCreated(new \DateTime());
      $manager->persist($page);
    }
    $manager->flush();
  }

  public function getDependencies() {
    return [
      TermFixtures::class,
      UserFixtures::class
    ];
  }
}