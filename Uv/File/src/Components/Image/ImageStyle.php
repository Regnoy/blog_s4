<?php

namespace Uv\File\Components\Image;

use Gumlet\ImageResize;
use Symfony\Component\Filesystem\Filesystem;
use Uv\Core\Components\Utils\SchemaReader;
use Uv\File\Entity\File;
use Uv\File\FileAssistant;

class ImageStyle
{
  private $fileSystem;

  private $fileManager;

  public $imageExtension = ['jpg', 'png', 'jpeg'];

  private $imageStyleList = [];

  public function __construct( SchemaReader $schema_reader, FileAssistant $file_assistant ) {
    $this->fileSystem = new Filesystem();
    $this->fileManager = $file_assistant;
    $this->schemaReader = $schema_reader;
    $this->imageStyleList =  $this->schemaReader->getSchema('image_style');
  }

  public function existStyle($style_name){
    return isset($this->imageStyleList[$style_name]);
  }
  public function getStyle($style_name){
    return $this->imageStyleList[$style_name];
  }

  /**
   * @param File $file
   * @param $style_name
   * @return string|null
   * @todo Check if File exists before imageStyle
   * @todo return original image Style
   */
  public function styleImage(File $file, $style_name)
  {
    $imagePublicUrl = $this->styleImageGenerate($file, $style_name);
    return $this->fileManager->webDir($imagePublicUrl);
  }
  public function styleImageGenerate(File $file, $style_name){
    if ($this->existStyle($style_name)) {
      $dirRoot = $this->fileManager->uploadFolderDir('image_style/' . $style_name);
      $fileRootDir = $dirRoot . '/' . $file->getFilename();
      $fileWebDir = "public://image_style/" . $style_name . "/" . $file->getFilename();
      if (is_file($fileRootDir)) {
        return $fileWebDir;
      }
      $this->resizeImage($file, $style_name, $fileRootDir);
      return $fileWebDir;
    }
    return null;
  }
  public function resizeImage(File $file, $style_name, $newPath = null){
    $url = $file->getUrl();
    if(!$newPath)
      $newPath = $this->fileManager->rootUrl($url);
    $imageResize = new ImageResize($this->fileManager->rootUrl($url));
    $this->actionResizeImage($imageResize, $style_name);
    $imageResize->save($newPath);
  }
  public function actionResizeImage(ImageResize $image_resize, $style_name){
    $styleImage = $this->getStyle($style_name);

    foreach ($styleImage['style'] as $name => $style){
      $action = $style['action'];
      $width = $style['width'];
      $height = $style['height'];

      switch ($action) {
        case "scale_crop":
          $image_resize->crop($width, $height, true);
          break;
        case "crop":
          $image_resize->crop($width, $height);
          break;
        case "resize":
          $image_resize->resize($width, $height);
          break;
        default:
          $image_resize->resizeToBestFit($width, $height);
          break;
      }
    }
  }

}