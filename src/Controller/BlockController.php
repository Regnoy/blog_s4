<?php

namespace App\Controller;

use App\Entity\Term;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class BlockController extends Controller {

  public function logo(){
    return $this->render('Block/logo.html.twig');
  }
  public function mainMenu(){
    return $this->render('Block/main-menu.html.twig');
  }
  public function mainMenuFooter(){
    return $this->render('Block/main-menu-footer.html.twig');
  }
  public function category(){
    $terms = $this->getDoctrine()->getRepository(Term::class)->findAll();
    return $this->render('Block/category-list.html.twig', [
      'terms' => $terms
    ]);
  }
}