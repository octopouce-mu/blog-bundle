<?php

namespace Octopouce\CmsBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;

/**
 * @ORM\Table(name="blog_post_translation")
 * @ORM\Entity()
 */
class PostTranslation
{
	use ORMBehaviors\Translatable\Translation;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="title", type="string", length=255)
	 */
	protected $title;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="slug", type="string", length=255)
	 */
	protected $slug;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="content", type="text", nullable=true)
	 */
	protected $content;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="seo_title", type="string", length=255, nullable=true)
	 */
	protected $seoTitle;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="seo_description", type="string", length=255, nullable=true)
	 */
	protected $seoDescription;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="og_title", type="string", length=255, nullable=true)
	 */
	protected $ogTitle;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="og_description", type="string", length=255, nullable=true)
	 */
	protected $ogDescription;



	public function __toString() {
		return $this->getTitle();
	}

	public function getTitle() {
		return $this->title;
	}

	public function setTitle( $title ) {
		$this->title = $title;
	}

	public function getSlug() {
		return $this->slug;
	}

	public function setSlug( $slug ) {
		$this->slug = $slug;
	}

	public function setContent($content)
	{
		$this->content = $content;
	}

	public function getContent()
	{
		return $this->content;
	}

	public function getSeoTitle() {
		return $this->seoTitle;
	}

	public function setSeoTitle( $seoTitle ) {
		$this->seoTitle = $seoTitle;
	}

	public function getSeoDescription() {
		return $this->seoDescription;
	}

	public function setSeoDescription( $seoDescription ) {
		$this->seoDescription = $seoDescription;
	}

	public function getOgTitle() {
		return $this->ogTitle;
	}

	public function setOgTitle( $ogTitle ) {
		$this->ogTitle = $ogTitle;
	}

	public function getOgDescription() {
		return $this->ogDescription;
	}

	public function setOgDescription( $ogDescription ) {
		$this->ogDescription = $ogDescription;
	}

}
