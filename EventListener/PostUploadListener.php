<?php
/**
 * Created by Kévin Hilairet <kevin@octopouce.mu>
 * Date: 06/06/2018
 */

namespace Octopouce\BlogBundle\EventListener;

use Octopouce\AdminBundle\Utils\FileUploader;
use Octopouce\BlogBundle\Entity\Post;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;

class PostUploadListener {


	public function postLoad(LifecycleEventArgs $args)
	{
		$entity = $args->getEntity();

		if (!$entity instanceof Post) {
			return;
		}

		$ogImage = $entity->getOgImage();

		if ($ogImage && file_exists($ogImage)) {
			$entity->setOgImage(new File($ogImage));
		} else{
			$entity->setOgImage(null);
		}

		$thumbnail = $entity->getThumbnail();
		if ($thumbnail && file_exists($thumbnail)) {
			$entity->setThumbnail(new File($thumbnail));
		} else{
			$entity->setThumbnail(null);
		}
	}
}