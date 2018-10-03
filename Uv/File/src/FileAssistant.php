<?php

namespace Uv\File;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\RouterInterface;
use Uv\File\Entity\File;


class FileAssistant implements FileAssistantInterface {
  const PUBLIC_FOLDER = 'files';
  const PRIVATE_FOLDER = 'private';

  private $requestStack;

  private $filesystem;

  private $router;

  public function __construct( RequestStack $requestStack, Filesystem $filesystem, RouterInterface $router) {
    $this->requestStack = $requestStack;
    $this->filesystem = $filesystem;
    $this->router = $router;
  }

  public function rootDir(){
    return $this->requestStack->getCurrentRequest()->server->get('DOCUMENT_ROOT');
  }

  public function checkFileFolder(){
    return is_dir($this->rootDir());
  }

  public function uploadFolderDir( $folder = null, $private = false){
    $rootDir = static::rootDir();
    $fileDir = $private ? $rootDir.'/'.static::PRIVATE_FOLDER : $rootDir.'/'.static::PUBLIC_FOLDER;
    if(!is_dir($fileDir)){
      $this->filesystem->mkdir($fileDir, 0755);
    }
    if( $folder ){
      $fileDir = $fileDir.'/'.$folder;
      $this->filesystem->mkdir($fileDir, 0755);
    }
    return $fileDir;
  }

  /**
   * @param $url
   * @return mixed
   */
  public function webDir( $url, $absolute = false ){
    $url = str_replace('public://', '/'.static::PUBLIC_FOLDER.'/', $url);
    if($absolute){
      $url = $this->router->getContext()->getHost().$url;
    }
    return $url;
  }
  public function webUrl(File $file, $absolute = false){
    if( $file->isPrivate() ){
      $url = str_replace("private://", '/'.static::PUBLIC_FOLDER.'/', $file->getUrl());
    } else {
      $url = str_replace("public://", '/'.static::PUBLIC_FOLDER.'/', $file->getUrl());
    }
    if($absolute){
      $url = $this->router->getContext()->getHost().$url;
    }
    return $url;
  }

  public function isFile( File $file ){
    return is_file($this->rootUrl($file->getUrl()));
  }
  public function isPrivate(File $file){
    return $file->isPrivate();
  }
  public function isPrivateUrl( $url ){
    return strpos($url, 'private://')  !== false;
  }
  public function rootUrl($url){
    $rootDir = static::rootDir();
    if( $this->isPrivateUrl($url) ){
      $url = str_replace("private://", $rootDir.'/'.static::PRIVATE_FOLDER .'/', $url);
    } else {
      $url = str_replace("public://", $rootDir.'/'.static::PUBLIC_FOLDER.'/', $url);
    }
    return $url;
  }

  /**
   * return string of webfolder in system;
   *
   * @param $folder
   * @param $fileName
   * @return string
   */
  public function getUrlFilePath($folder, $fileName){
    return 'public://'.$folder.'/'.$fileName;
  }

  public function getUrlPrivatePath($folder, $fileName){
    return 'private://'.$folder.'/'.$fileName;
  }

  /**
   * @param \Symfony\Component\HttpFoundation\File\UploadedFile $upload_file
   * @param string $folder
   * @param bool $private
   * @return File
   */
  public function prepareUploadFile(UploadedFile $upload_file, $folder, $private = false ){
    $fileName = md5(uniqid()).'.'.$upload_file->getClientOriginalExtension();
    $file = new File();
    $file->setUploadFile($upload_file);
    $file->setFilename($fileName);
    $file->setFileSize( $upload_file->getSize());
    $file->setFileMime($upload_file->getMimeType());
    $file->setOriginName($upload_file->getClientOriginalName());
    $file->setStatus(0);
    if($private){
      $url = $this->getUrlPrivatePath($folder, $file->getFilename());
    } else {
      $url = $this->getUrlFilePath($folder, $file->getFilename());
    }
    $file->setUrl($url);
    return $file;
  }
  public function setFolderFile(File $file, $folder){
    if($file->isPrivate()){
      $url = $this->getUrlPrivatePath($folder, $file->getFilename());
    } else {
      $url = $this->getUrlFilePath($folder, $file->getFilename());
    }
    $file->setUrl($url);
  }

  public function folderUploadDir(File $file){
    return $this->uploadFolderDir($file->getFolder(), !is_null($file->getHandler()));
  }
  public function uploadFileValidate( UploadedFile $upload_file, array $extension ){
    return in_array($upload_file->getClientOriginalExtension(), $extension);
  }

  public function isImageMimeType( $url_root ){
    $mimeType = ["image/png", "image/jpeg"];
    return in_array(mime_content_type($url_root), $mimeType);
  }

  public function getFolderMonthYear(){
    $date = new \DateTime();
    return date('m-Y', $date->getTimestamp());
  }
}