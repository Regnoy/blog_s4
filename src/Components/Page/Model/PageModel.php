<?php

namespace App\Components\Page\Model;

use App\Entity\Page;

use Symfony\Component\Validator\Constraints as Assert;

class PageModel
{

  private $id;

  private $pageId;

  private $language;

  /**
   * @Assert\NotBlank()
   */
  private $title;

  private $summary;

  /**
   * @Assert\NotBlank()
   */
  private $body;

  private $image;

  /**
   * @Assert\NotBlank()
   */
  private $category;

  private $page;

    public function attachPage(Page $page , $language){
      $this->language = $language;
      $this->page = $page;
      $data = $page->getEntity($language);
      if($language != $page->getLanguage() && is_null($data->getId())){
        $data = $page->getEntity($page->getLanguage());
      }
      $this->id = $page->getId();
      $this->title = $data->getTitle();
      $bodyData = $data->getFieldBody();
      if($bodyData) {
        $this->summary = $bodyData->getSummary();
        $this->body = $bodyData->getBody();
      }
      $category = $data->getCategory();
    if($category){
      $this->category = $category->getId();
    }

  }

  /**
   * @return mixed
   */
  public function getId()
  {
    return $this->id;
  }

  /**
   * @param mixed $id
   */
  public function setId($id): void
  {
    $this->id = $id;
  }

  /**
   * @return mixed
   */
  public function getLanguage()
  {
    return $this->language;
  }

  /**
   * @param mixed $language
   */
  public function setLanguage($language): void
  {
    $this->language = $language;
  }

  /**
   * @return mixed
   */
  public function getTitle()
  {
    return $this->title;
  }

  /**
   * @param mixed $title
   */
  public function setTitle($title): void
  {
    $this->title = $title;
  }

  /**
   * @return mixed
   */
  public function getSummary()
  {
    return $this->summary;
  }

  /**
   * @param mixed $summary
   */
  public function setSummary($summary): void
  {
    $this->summary = $summary;
  }

  /**
   * @return mixed
   */
  public function getBody()
  {
    return $this->body;
  }

  /**
   * @param mixed $body
   */
  public function setBody($body): void
  {
    $this->body = $body;
  }

  /**
   * @return mixed
   */
  public function getCategory()
  {
    return $this->category;
  }

  /**
   * @param mixed $category
   */
  public function setCategory($category): void
  {
    $this->category = $category;
  }

  /**
   * @return Page
   */
  public function getPage()
  {
    return $this->page;
  }

  /**
   * @return mixed
   */
  public function getImage()
  {
    return $this->image;
  }

  /**
   * @param mixed $image
   */
  public function setImage($image): void
  {
    $this->image = $image;
  }


}