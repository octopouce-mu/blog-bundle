<?php
/**
 * Created by Kévin Hilairet <kevin@octopouce.mu>
 * Date: 06/06/2018
 */

namespace Octopouce\BlogBundle\EventListener;

use Octopouce\BlogBundle\Utils\FileUploader;
use Octopouce\BlogBundle\Entity\Post;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;

class PostUploadListener {

	private $uploader;

	public function __construct(FileUploader $uploader)
	{
		$this->uploader = $uploader;
	}

	public function postPersist(LifecycleEventArgs $args)
	{
		$entity = $args->getEntity();

		if (!$entity instanceof Post) {
			return;
		}

		$this->uploadFile($entity);
		$entityManager = $args->getObjectManager();
		$entityManager->flush();
	}

	public function preUpdate(PreUpdateEventArgs $args)
	{
		$entity = $args->getEntity();

		$this->uploadFile($entity);
	}

	public function postLoad(LifecycleEventArgs $args)
	{
		$entity = $args->getEntity();

		if (!$entity instanceof Post) {
			return;
		}

		$dir = $this->uploader->getTargetDirectory().'/'.$entity->getId();
		if ($entity->getImage() && file_exists($dir.'/'.$entity->getImage())) {
			$entity->setImage(new File($dir.'/'.$entity->getImage()));
		}else{
			$entity->setImage(null);
		}
	}

	private function uploadFile($entity)
	{
		// upload only works for Post entities
		if (!$entity instanceof Post) {
			return;
		}

		$file = $entity->getImage();

		// only upload new files
		if ($file instanceof UploadedFile) {
			$imgName = $this->uploader->upload($file, $entity->getId());
			$entity->setImage($imgName);
		}elseif($file instanceof File){
			$entity->setImage($file->getFilename());
		}

	}
}