<?php


namespace App\Controller;


use App\Entity\Comment;
use App\Forms\CommentForm;
use App\Entity\Page;
use App\Forms\PageDeleteForm;
use App\Forms\PageForm;
use App\Forms\SearchForm;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;


class PageController extends Controller {

  public function list( Request $request ){
    $pageRepo = $this->getDoctrine()->getRepository(Page::class);
    $pager = $request->query->get('page') ? $request->query->get('page') : 1;
    $limit = 2;
    $pages = $pageRepo->findPages($pager, $limit);
    $pager = [
      'pager' => $pager,
      'total' => $pageRepo->countPage(),
      'limit' => $limit
    ];
    return $this->render('Page/list.html.twig',[
      'pages' => $pages,
      'navigator' => $pager
    ]);
  }

  public function view($id, Request $request){
    $pageRepo = $this->getDoctrine()->getRepository(Page::class);
    /** @var Page $page */
    $page = $pageRepo->find($id);
    if(!$page){
      throw $this->createNotFoundException('The page does not exist');
    }
    $em = $this->getDoctrine()->getManager();
    $commentForm = $this->createForm(CommentForm::class);
    $commentForm->handleRequest($request);
    if($commentForm->isSubmitted()){
      /** @var Comment $comment */
      $comment = $commentForm->getData();
      $comment->setPage($page);

      $em->persist($comment);
      $em->flush();
      return $this->redirectToRoute('page_view', ['id' => $page->getId()]);
    }
    $commentRepo = $em->getRepository(Comment::class);
    $comments = $commentRepo->findLastComments($page, 10);

    return $this->render('Page/view.html.twig',[
      'page' => $page,
      'comment_form' => $commentForm->createView(),
      'page_comments' => $comments
    ]);
  }

  public function add( Request $request ){
    $page = new Page();
    $form = $this->createForm(PageForm::class, $page );
    $form->handleRequest($request);
    if($form->isSubmitted()){
      $em = $this->getDoctrine()->getManager();
      $em->persist($page);
      $em->flush();
      return $this->redirectToRoute('page_list');
    }
    return $this->render('Page/add.html.twig', [
      'form' => $form->createView()
    ]);
  }


  public function edit($id, Request $request){
//    $request = $this->get('request_stack')->getCurrentRequest();

    $em = $this->getDoctrine()->getManager();
    $repo = $em->getRepository(Page::class);
    $page = $repo->find($id);
    if(!$page)
      return $this->redirectToRoute('page_list');

    $form = $this->createForm(PageForm::class, $page );
    $form->handleRequest($request);
    if($form->isSubmitted()){
      $em->persist($page);
      $em->flush();
      return $this->redirectToRoute('page_view', [ 'id' => $page->getId() ]);
    }
    return $this->render('Page/edit.html.twig', [
      'form' => $form->createView()
    ]);
  }
  public function remove($id, Request $request){
    $em = $this->getDoctrine()->getManager();
    $repo = $em->getRepository(Page::class);
    $page = $repo->find($id);
    if(!$page)
      return $this->redirectToRoute('page_list');

    $form = $this->createForm(PageDeleteForm::class, null, [
      'delete_id' => $page->getId()
    ] );

    $form->handleRequest($request);
    if($form->isSubmitted()){
      $em->remove($page);
      $em->flush();

      return $this->redirectToRoute('page_list');
    }
    return $this->render('Page/delete.html.twig', [
      'form' => $form->createView()
    ]);
  }
  public function comments($id, Request $request){
    $pageRepo = $this->getDoctrine()->getRepository(Page::class);
    /** @var Page $page */
    $page = $pageRepo->find($id);
    if(!$page){
      throw $this->createNotFoundException('The page does not exist');
    }
    $pager = $request->query->get('pager') ? $request->query->get('pager') : 1;
    $limit = 10;
    $commentRepo = $this->getDoctrine()->getRepository(Comment::class);
    $comments = $commentRepo->findComments($page, $pager, $limit);

    $pager = [
      'pager' => $pager,
      'total' => $commentRepo->countComments($page),
      'limit' => $limit
    ];

    return $this->render('Page/page_comments.html.twig',[
      'page' => $page,
      'comments' => $comments,
      'navigator' => $pager
    ]);
  }
  public function search( Request $request ){
    $pageRepo = $this->getDoctrine()->getRepository(Page::class);
    $searchForm = $this->createForm(SearchForm::class);
    $searchForm->handleRequest($request);
    $pages = null;
    if($searchForm->isSubmitted()){
      $data = $searchForm->getData();
      $pages = $pageRepo->findByWord($data['search']);
    }
    return $this->render('PageBundle:Page:search.html.twig',[
      'pages' => $pages,
      'form' => $searchForm->createView()
    ]);
  }

}