<?php


namespace Uv\File\Events;

use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Uv\File\Entity\File;
use Uv\File\FileAssistant;

class FileUploadSubscriber implements EventSubscriber
{

  private $fileAssistant;

  public function __construct(FileAssistant $fileAssistant)
  {
    $this->fileAssistant = $fileAssistant;
  }

  public function getSubscribedEvents()
	{
		return[
			'postPersist',
      'preRemove'
		];
	}

	public function preRemove( LifecycleEventArgs $args ){
    $entity = $args->getObject();
    if ($entity instanceof File) {
      $file = $this->fileAssistant->rootUrl( $entity->getUrl() );
      if(is_file($file))
        @unlink( $this->fileAssistant->rootUrl( $entity->getUrl() ) );
    }
  }

	public function postPersist(LifecycleEventArgs $args)
	{
	  $entity = $args->getObject();
		if ($entity instanceof File && $entity->getUploadFile()) {
      $uploadFile = $entity->getUploadFile();

    	$folderUploadDir = $this->fileAssistant->folderUploadDir($entity);
      $uploadFile->move($folderUploadDir, $entity->getFilename());
      $entity->setUploadFile(null);
    }
	}
}