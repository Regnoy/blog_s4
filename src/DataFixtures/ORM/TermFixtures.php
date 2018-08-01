<?php
/**
 * Created by PhpStorm.
 * User: pavel
 * Date: 10/9/2017
 * Time: 9:46 AM
 */

namespace App\DataFixtures\ORM;


use App\Entity\Term;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;


class TermFixtures extends Fixture {

  public function load(ObjectManager $manager) {

    $term = new Term();
    $term->setMachineName("term_one");
    $manager->persist($term);
    $term = new Term();
    $term->setMachineName("term_two");
    $manager->persist($term);
    $term = new Term();
    $term->setMachineName("term_three");
    $manager->persist($term);
    $manager->flush();

  }
}