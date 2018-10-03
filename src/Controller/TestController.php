<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Uv\File\FileAssistant;

class TestController extends Controller
{
  /**
   * @return \Symfony\Component\HttpFoundation\Response
   * @Route("test_file", name="test_file")
   */
  public function testFile(FileAssistant $fileAssistant){
    dump($this->get('uv.file.file_assistant'));
    dd($fileAssistant->rootDir());
    return $this->render('System/base.html.twig');
  }
}