<?php

namespace App\Controller;


use App\Entity\Page;
use App\Entity\Term;
use App\Forms\TermDeleteForm;
use App\Forms\TermForm;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;


class TermController extends Controller {

  public function list(){
    $terms = $this->getDoctrine()->getRepository(Term::class)->findAll();


    return $this->render('Term/list.html.twig', [
      'terms' => $terms
    ]);
  }

  public function add(Request $request){

    $term = new Term();
    $form = $this->createForm(TermForm::class, $term );
    $form->handleRequest($request);
    if($form->isSubmitted()){
      $em = $this->getDoctrine()->getManager();
      $em->persist($term);
      $em->flush();
      return $this->redirectToRoute('term_list');
    }
    return $this->render('Term/add.html.twig', [
      'form' => $form->createView()
    ]);

  }
  public function view($id){
    $repo = $this->getDoctrine()->getRepository(Term::class);
    /** @var Term $term */
    $term = $repo->find($id);
    if(!$term){
      throw $this->createNotFoundException('The term does not exist');
    }
    $pageRepo = $this->getDoctrine()->getRepository(Page::class);
    $pages = $pageRepo->findByTerms($term);
    return $this->render('Term/view.html.twig',[
      'term' => $term,
      'pages' => $pages
    ]);
  }
  public function edit($id, Request $request){
    $em = $this->getDoctrine()->getManager();
    $repo = $em->getRepository(Term::class);
    $term = $repo->find($id);
    if(!$term)
      return $this->redirectToRoute('term_list');

    $form = $this->createForm(TermForm::class, $term );
    $form->handleRequest($request);
    if($form->isSubmitted()){
      $em->persist($term);
      $em->flush();
      return $this->redirectToRoute('term_view', [ 'id' => $term->getId() ]);
    }
    return $this->render('Term/edit.html.twig', [
      'form' => $form->createView()
    ]);
  }

  public function delete($id, Request $request){
    $em = $this->getDoctrine()->getManager();
    $repo = $em->getRepository(Term::class);
    $term = $repo->find($id);
    if(!$term)
      return $this->redirectToRoute('term_list');

    $form = $this->createForm(TermDeleteForm::class, null, [
      'delete_id' => $term->getId()
    ] );

    $form->handleRequest($request);
    if($form->isSubmitted()){
      $em->remove($term);
      $em->flush();

      return $this->redirectToRoute('term_list');
    }
    return $this->render('Term/delete.html.twig', [
      'form' => $form->createView()
    ]);
  }

}