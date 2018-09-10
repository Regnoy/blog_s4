<?php

namespace App\Controller;

use App\Components\Language\CurrentLanguage;
use App\Components\Language\LanguageManager;
use App\Components\Page\Form\PageForm;
use App\Components\Page\Model\PageModel;
use App\Components\Page\PageManager;
use App\Entity\Comment;
use App\Forms\CommentForm;
use App\Entity\Page;
use App\Forms\PageDeleteForm;
use App\Forms\SearchForm;
use App\Voter\PageVoter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;


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

  public function view($id, Request $request, FlashBagInterface $flashBag){
    //EN, RU
//    CurrentLanguage::$language = 'fr';
    $pageRepo = $this->getDoctrine()->getRepository(Page::class);
    /** @var Page $page */
    $page = $pageRepo->find($id);
    if(!$page){
      throw $this->createNotFoundException('The page does not exist');
    }
    $pageData = $page->getEntity();
    if(!$pageData){
      throw $this->createNotFoundException('Translation page does not exist');
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
      'page_data' => $pageData,
      'comment_form' => $commentForm->createView(),
      'page_comments' => $comments
    ]);
  }

  public function add( Request $request, FlashBagInterface $flashBag, PageManager $pageManager ){
    $transLg = $request->query->get('trans_lg') ?? CurrentLanguage::$language;
    $trandId= $request->query->get('trand_id');
    $repo = $pageManager->getPageRepo();
    /** @var Page $page */
    $page = $repo->find($trandId);
    if($page){
      $this->denyAccessUnlessGranted(PageVoter::EDIT, $page);
      $entities = $page->getEntities();
      if(isset($entities[$transLg])){
        return $this->redirectToRoute('page_edit', ['id' => $page->getId(), 'trans_lg' => $transLg]);
      }
    } else {
      $page = new Page();
    }

    $pageModel = new PageModel();
    $pageModel->attachPage($page, $transLg);

    $form = $this->createForm(PageForm::class, $pageModel, [
      'entity_manager' => $this->getDoctrine()->getManager()
    ]);
    $form->handleRequest($request);
    if($form->isSubmitted() && $form->isValid()){

      $pageManager->save($form->getData());
      return $this->redirectToRoute('page_list');
    }
    return $this->render('Page/add.html.twig', [
      'form' => $form->createView()
    ]);
  }

  /**
   * @param $id
   * @param Request $request
   * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
   * @IsGranted("ROLE_USER", statusCode=404, message="Article not found")
   */
//*
  public function edit($id, Request $request, FlashBagInterface $flashBag, PageManager $pageManager){
//    $request = $this->get('request_stack')->getCurrentRequest();
    $transLg = $request->query->get('trans_lg') ?? CurrentLanguage::$language;
    $repo = $pageManager->getPageRepo();
    /** @var Page $page */
    $page = $repo->find($id);
    if(!$page)
      return $this->redirectToRoute('page_list');

    $this->denyAccessUnlessGranted(PageVoter::EDIT, $page);
    $pageModel = new PageModel();

    $pageModel->attachPage($page, $transLg);
    $form = $this->createForm(PageForm::class, $pageModel,[
      'entity_manager' => $pageManager->getEm()
    ] );
    $form->handleRequest($request);
    if($form->isSubmitted() && $form->isValid()){
      $pageManager->save($pageModel);
      $flashBag->add('success', 'Article is edited:'. $page->getEntity($transLg)->getTitle());
      return $this->redirectToRoute('page_view', [ 'id' => $page->getId(), '_locale' => $transLg ]);
    }
    return $this->render('Page/edit.html.twig', [
      'form' => $form->createView()
    ]);
  }
  public function remove($id, Request $request, SessionInterface $session){
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
      $session->getFlashBag('success', 'Article is deleted:'. $page->getTitle());
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

  /**
   * @param Request $request
   * @return \Symfony\Component\HttpFoundation\Response
   * @Route("page-ajax", name="page_list_ajax")
   */
  public function listAjax( Request $request ){
    $pageRepo = $this->getDoctrine()->getRepository(Page::class);

    $limit = 2;

    $pages = $pageRepo->findPages(1, $limit);

    return $this->render('Page/list-ajax.html.twig',[
      'pages' => $pages,
    ]);
  }

  /**
   * @param Request $request
   * @return Response
   * @Route("page-api", name="page_api", defaults={"_format"="json"})
   */
  public function pageApi( Request $request, \Twig_Environment $twig ){
    $pager = $request->query->get('page') ? $request->query->get('page') : 1;
    $limit = 2;
    $pageRepo = $this->getDoctrine()->getRepository(Page::class);
    $pages = $pageRepo->findPages($pager, $limit);
    $rs = [];
    $response = [
//      'messages' => [
//        'success' => [],
//        'warning' => []
//      ],
      'result' => [],
      'empty' => null
    ];
    if($pages){
      foreach ($pages as $page){
        $rs[] = $twig->render('Page/ViewMode/teaser.html.twig', [
          'item' => $page
        ]);
      }
      $response['result'] = $rs;
    } else {
      $response['empty'] = 1;
    }
    $jsonResponse = new JsonResponse();
    $jsonResponse->headers->set('Content-Type', 'application/json');
    $jsonResponse->setEncodingOptions(JSON_UNESCAPED_UNICODE);
    $jsonResponse->setData($response);
    return $jsonResponse;
  }
  public function translation($id, LanguageManager $languageManager){
    $languages = $languageManager->getLanguages();
    $pageRepo = $this->getDoctrine()->getRepository(Page::class);
    /** @var Page $page */
    $page = $pageRepo->find($id);
    if(!$page){
      throw $this->createNotFoundException('The page does not exist');
    }


    return $this->render('Page/translation.html.twig',[

      'page' => $page,
      'languages' => $languages
    ]);
  }

}