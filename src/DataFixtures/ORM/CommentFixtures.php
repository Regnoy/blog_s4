<?php

namespace App\DataFixtures\ORM;


use App\Entity\Comment;
use App\Entity\Page;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class CommentFixtures extends Fixture implements DependentFixtureInterface {

  public function load(ObjectManager $manager) {
//    $pageRepo = $manager->getRepository(Page::class);
//    $pages = $pageRepo->findAll();
//    foreach ($pages as $page){
//      for( $i = 1; $i <=15; $i++){
//        $comment = new Comment();
//        $comment->setComment('Comment '.$i. ' > '.$page->getTitle());
//        $page->addComment($comment);
//        $comment->setPage($page);
//      }
//      $manager->persist($page);
//    }
//    $manager->flush();
  }
  public function getDependencies() {
    return [
      PageFixtures::class
    ];
  }
}