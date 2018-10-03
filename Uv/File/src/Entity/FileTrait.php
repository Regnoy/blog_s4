<?php

namespace Uv\File\Entity;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Doctrine\ORM\Mapping as ORM;


trait FileTrait
{
  /**
   * @ORM\Column(type="integer")
   * @ORM\Id
   * @ORM\GeneratedValue(strategy="AUTO")
   */
  private $id;

  /**
   * @ORM\Column(type="integer", name="user_id", nullable=true )
   */
  private $user;
  /**
   * @ORM\Column(type="string")
   */
  private $filename;
  /**
   * @ORM\Column(type="string")
   */
  private $originName;

  /**
   * @ORM\Column(type="string")
   */
  private $url;

  /**
   * @ORM\Column(type="integer")
   */
  private $fileSize;

  /**
   * @ORM\Column(type="string")
   */
  private $fileMime;

  /**
   * @ORM\Column(type="smallint")
   */
  private $status;

  /**
   * @ORM\Column(type="datetime")
   */
  private $created;

  /**
   * @ORM\Column(type="string", nullable=true)
   */
  private $handler;

  private $uploadFile;

  private $isPrivate = false;



  public function __construct() {
    $this->created = new \DateTime();
    $this->fileSize = 0;
    $this->status = FileBaseInterface::TEMPORARY;
  }

  /**
   * Return folder of existed file in system
   */
  public function getFolder(){
    $url = $this->getUrl();
    $url = str_replace('public://', '', $url);
    $url = str_replace('private://', '', $url);
    $url = explode('/',$url);
    array_pop($url);
    return implode('/', $url);
  }

  public function isPrivate(){
    return !is_null($this->handler);
  }
  /**
   * Get id
   *
   * @return integer
   */
  public function getId()
  {
    return $this->id;
  }

  /**
   * Set user
   *
   * @param integer $user
   *
   * @return File
   */
  public function setUser($user)
  {
    $this->user = $user;
  }

  /**
   * Get user
   *
   * @return integer
   */
  public function getUser()
  {
    return $this->user;
  }

  /**
   * Set filename
   *
   * @param string $filename
   *
   * @return File
   */
  public function setFilename($filename)
  {
    $this->filename = $filename;
  }

  /**
   * Get filename
   *
   * @return string
   */
  public function getFilename()
  {
    return $this->filename;
  }

  /**
   * Set url
   *
   * @param string $url
   *
   * @return File
   */
  public function setUrl($url)
  {
    $this->url = $url;
  }

  /**
   * Get url
   *
   * @return string
   */
  public function getUrl()
  {
    return $this->url;
  }

  /**
   * Set fileSize
   *
   * @param integer $fileSize
   *
   * @return File
   */
  public function setFileSize($fileSize)
  {
    $this->fileSize = $fileSize;
  }

  /**
   * Get fileSize
   *
   * @return integer
   */
  public function getFileSize()
  {
    return $this->fileSize;
  }

  /**
   * Set fileMime
   *
   * @param string $fileMime
   *
   * @return File
   */
  public function setFileMime($fileMime)
  {
    $this->fileMime = $fileMime;
  }

  /**
   * Get fileMime
   *
   * @return string
   */
  public function getFileMime()
  {
    return $this->fileMime;
  }

  /**
   * Set status
   *
   * @param integer $status
   *
   * @return File
   */
  public function setStatus($status)
  {
    $this->status = $status;
  }

  /**
   * Get status
   *
   * @return integer
   */
  public function getStatus()
  {
    return $this->status;
  }

  /**
   * Set created
   *
   * @param \DateTime $created
   *
   * @return File
   */
  public function setCreated($created)
  {
    $this->created = $created;
  }

  /**
   * Get created
   *
   * @return \DateTime
   */
  public function getCreated()
  {
    return $this->created;
  }

  /** @return UploadedFile */
  public function getUploadFile(){
    return $this->uploadFile;
  }

  public function setUploadFile( UploadedFile $upload_file = null){
    $this->uploadFile = $upload_file;
    return $this;
  }

  /**
   * Set handler
   *
   * @param string $handler
   *
   * @return File
   */
  public function setHandler($handler)
  {
    $this->handler = $handler;
  }

  /**
   * Get handler
   *
   * @return string
   */
  public function getHandler()
  {
    return $this->handler;
  }



  /**
   * @return mixed
   */
  public function getOriginName() {
    if(!$this->originName)
      return $this->filename;

    return $this->originName;
  }

  /**
   * @param mixed $originName
   */
  public function setOriginName($originName) {
    $this->originName = $originName;
  }

}