<?php

namespace App\Controller;

use App\Components\Comments\Form\CommentViewForm;
use App\Entity\Comment;

use App\Voter\PageVoter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Workflow\Exception\TransitionException;
use Symfony\Component\Workflow\Registry;

class CommentController extends Controller {

  public function editAction($id, Request $request){
    $em = $this->getDoctrine()->getManager();
    $repo = $em->getRepository('CommentBundle:Comment');
    $comment = $repo->find($id);
    if(!$comment)
      return $this->redirectToRoute('page_list');

    $form = $this->createForm(CommentForm::class, $comment );
    $form->handleRequest($request);
    if($form->isSubmitted()){
      $em->persist($comment);
      $em->flush();
      return $this->redirectToRoute('comment_edit', [ 'id' => $comment->getId() ]);
    }
    return $this->render('@Comment/edit.html.twig', [
      'form' => $form->createView()
    ]);
  }

  public function removeAction($id, Request $request){
    $em = $this->getDoctrine()->getManager();
    $repo = $em->getRepository('CommentBundle:Comment');
    $comment = $repo->find($id);
    if(!$comment)
      return $this->redirectToRoute('page_list');

    $form = $this->createForm(CommentDeleteForm::class, null, [
      'delete_id' => $comment->getId()
    ] );

    $form->handleRequest($request);
    if($form->isSubmitted()){
      $page = $comment->getPage();
      $em->remove($comment);
      $em->flush();

      return $this->redirectToRoute('page_view',[
        'page' => $page->getId()
      ]);
    }
    return $this->render('CommentBundle::delete.html.twig', [
      'form' => $form->createView()
    ]);
  }

  public function view($id, Request $request, Registry $workflows){
    $em = $this->getDoctrine()->getManager();
    $repo = $em->getRepository(Comment::class);
    /** @var Comment $comment */
    $comment = $repo->find($id);
    if(!$comment)
      return $this->redirectToRoute('page_list');
    $this->denyAccessUnlessGranted(PageVoter::EDIT, $comment->getPage());
    $form = $this->createForm( CommentViewForm::class );
    $form->handleRequest($request);
    if($form->isSubmitted()){
      $data = $form->getData();
      $workflow = $workflows->get($comment);
      try {
        $workflow->apply($comment, $data['workflow']);
        $em->persist($comment);
        $em->flush();
      }catch (TransitionException  $exception) {
        dd($exception->getMessage());
      }

      return $this->redirectToRoute('page_view',[
        'id' => $comment->getPage()->getId()
      ]);
    }
    return $this->render('Comments/view.html.twig', [
      'comment' => $comment,
      'form' => $form->createView()
    ]);
  }
}