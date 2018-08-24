<?php
/**
 * Created by PhpStorm.
 * User: pavel
 * Date: 10/9/2017
 * Time: 9:46 AM
 */

namespace App\DataFixtures\ORM;


use App\Entity\Page;
use App\Entity\PageBody;
use App\Entity\PageData;
use App\Entity\Term;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;


class PageFixtures extends Fixture implements DependentFixtureInterface {

  public function load(ObjectManager $manager) {

    $termRepo = $manager->getRepository(Term::class);
    $user = $manager->getRepository(User::class)->findOneByEmail('info@utilvideo.com');
    $terms = $termRepo->findAll();
    /** @var Term $term */
    foreach ($terms as $term){
      $page = new Page();
      $page->setUser($user);

      $pageData = new PageData();
      $pageData->setLanguage($page->getLanguage());
      $page->addData($pageData);
      $pageData->setTitle('Page '.$term->getId());
      $pageData->setCreated(new \DateTime());
      $pageBody = new PageBody();
      $pageBody->setBody('Body article '.$term->getId());
      $pageBody->setSummary('Summary article '.$term->getId());
      $pageData->addBody($pageBody);

      $pageData = new PageData();
      $pageData->setLanguage('ru');
      $page->addData($pageData);
      $pageData->setTitle('Page RU'.$term->getId());
      $pageData->setCreated(new \DateTime());
      $pageBody = new PageBody();
      $pageBody->setBody('Body article RU'.$term->getId());
      $pageBody->setSummary('Summary article RU'.$term->getId());
      $pageData->addBody($pageBody);
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