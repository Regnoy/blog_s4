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
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;


class PageFixtures extends Fixture {

  public function load(ObjectManager $manager) {
    $termRepo = $manager->getRepository(Term::class);
    for ($i = 1 ; $i <= 3; $i++){
      $page = new Page();
      $page->setTitle('Page '.$i);
      $page->setBody('Body Page'. $i);
      $term = $termRepo->findOneByName('Term '.$i);
      if($term){
        $page->setCategory($term);
      }
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