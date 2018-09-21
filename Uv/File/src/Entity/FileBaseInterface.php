<?php


namespace Uv\File\Entity;


use Symfony\Component\HttpFoundation\File\UploadedFile;

interface FileBaseInterface
{
  const TEMPORARY = 0;
  const PERMANENT = 1;
  public function isPrivate();
  public function getId();
  public function setUser($user);
  public function getUser();
  public function setFilename($filename);
  public function getFilename();
  public function setUrl($url);
  public function getUrl();
  public function setFileSize($fileSize);
  public function getFileSize();
  public function setFileMime($fileMime);
  public function getFileMime();
  public function setStatus($status);
  public function getStatus();
  public function setCreated($created);
  public function getCreated();
  public function getUploadFile();
  public function setUploadFile( UploadedFile $upload_file = null);
  public function setHandler($handler);
  public function getHandler();
  public function getOriginName();
  public function setOriginName($originName);
}