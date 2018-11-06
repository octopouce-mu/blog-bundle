<?php
/**
 * Created by Kévin Hilairet <kevin@octopouce.mu>
 * Date: 28/06/2018
 */

namespace Octopouce\BlogBundle\EventListener;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Category;
use Octopouce\BlogBundle\Entity\Tag;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class PostFormListener implements EventSubscriberInterface
{
	private $em;

	/**
	 * PostFormListener constructor.
	 *
	 * @param $em
	 */
	public function __construct(EntityManagerInterface $em ) {
		$this->em = $em;
	}


	public static function getSubscribedEvents()
	{
		return array(
			FormEvents::PRE_SUBMIT   => 'onPreSubmit',
		);
	}


	public function onPreSubmit(FormEvent $event)
	{
		$data = $event->getData();

		if (!$data) {
			return;
		}

		if(isset($data['categories'])) {
			foreach ( $data['categories'] as $key => $category ) {
				if ( ! is_numeric( $category ) ) {
					$new = new Category();
					$new->setName( $category );
					$new->setType( 'blog' );
					$new->setSlug( $this->slugify( $category ) );
					$this->em->persist( $new );
					$this->em->flush();
					$data['categories'][ $key ] = "" . $new->getId();
				}
			}
		}

		if(isset($data['tags'])) {
			foreach ( $data['tags'] as $key => $tag ) {
				if ( ! is_numeric( $tag ) ) {
					$new = new Tag();
					$new->setName( $tag );
					$new->setSlug( $this->slugify( $tag ) );
					$this->em->persist( $new );
					$this->em->flush();
					$data['tags'][ $key ] = "".$new->getId();
				}
			}
		}
		$event->setData($data);
	}

	private function slugify($text)
	{
		// replace non letter or digits by -
		$text = preg_replace('~[^\pL\d]+~u', '-', $text);

		// transliterate
		$text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

		// remove unwanted characters
		$text = preg_replace('~[^-\w]+~', '', $text);

		// trim
		$text = trim($text, '-');

		// remove duplicate -
		$text = preg_replace('~-+~', '-', $text);

		// lowercase
		$text = strtolower($text);

		if (empty($text)) {
			return 'n-a';
		}

		return $text;
	}
}