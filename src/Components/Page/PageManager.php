<?php

namespace App\Components\Page;

use App\Components\Language\LanguageManager;
use App\Components\Page\Model\PageModel;
use App\Entity\Page;
use App\Entity\PageBody;
use App\Entity\Term;
use Doctrine\ORM\EntityManagerInterface;

class PageManager
{

  private $em;

  private $languageManager;

  public function __construct(EntityManagerInterface $entityManager, LanguageManager $languageManager)
  {
    $this->em = $entityManager;
    $this->languageManager = $languageManager;
  }

  public function save(PageModel $pageModel){
    $data = $pageModel->getPage()->getEntity($pageModel->getLanguage());
    $data->setTitle( $pageModel->getTitle() );
    $bodyData = $data->getFieldBody();
    if(!$bodyData){
      $bodyData = new PageBody();
      $data->addBody($bodyData);
    }
    $bodyData->setSummary($pageModel->getSummary());
    $bodyData->setBody($pageModel->getBody());
    $category = $pageModel->getCategory();
    $data->setCategory($category);
    $page = $pageModel->getPage();
    $this->em->persist($page);
    $this->em->flush();

  }
  public function getTerms(){
    $lists = $this->em->getRepository(Term::class)->findAll();
    $terms = [];
    foreach ($lists as $list){
      $terms[$list->getId()] = $list;
    }
    return $terms;
  }

  /**
   * @return \App\Repository\PageRepository|\Doctrine\Common\Persistence\ObjectRepository
   */
  public function getPageRepo(){
    return $this->em->getRepository(Page::class);
  }

  /**
   * @return LanguageManager
   */
  public function getLanguageManager(): LanguageManager
  {
    return $this->languageManager;
  }


}