<?php

namespace Octopouce\BlogBundle\Entity;

use App\Entity\Account\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Octopouce\AdminBundle\Entity\Category;
use Octopouce\AdminBundle\Entity\File;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;
use Octopouce\AdminBundle\Translatable\Translatable;

/**
 * @ORM\Table(name="blog_post")
 * @ORM\Entity(repositoryClass="Octopouce\BlogBundle\Repository\PostRepository")
 */
class Post
{
	use Translatable;

	/**
	 * @var int
	 *
	 * @ORM\Column(name="id", type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;

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
	 * @var bool
	 *
	 * @ORM\Column(name="enabled", type="boolean")
	 */
	protected $enabled;

	/**
	 * @var \DateTime
	 *
	 * @ORM\Column(name="published_at", type="datetime")
	 */
	protected $publishedAt;

	/**
	 * @var \DateTime
	 *
	 * @ORM\Column(name="created_at", type="datetime")
	 */
	protected $createdAt;

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

	/**
	 * @var string
	 *
	 * @ORM\Column(name="og_image", type="string", length=255, nullable=true)
	 */
	protected $ogImage;

	/**
	 * @ORM\Column(type="string", length=255, nullable=true)
	 */
	private $thumbnail;

	/**
	 * @var User
	 *
	 * @ORM\ManyToOne(targetEntity="App\Entity\Account\User")
	 */
	private $user;

	/**
	 * @ORM\ManyToMany(targetEntity="Octopouce\AdminBundle\Entity\Category")
	 * @ORM\JoinTable(name="blog_post_category",
	 *     joinColumns={@ORM\JoinColumn(name="post_id", referencedColumnName="id")},
	 *     inverseJoinColumns={@ORM\JoinColumn(name="category_id", referencedColumnName="id")})
	 */
	private $categories;

	/**
	 * @ORM\ManyToMany(targetEntity="Octopouce\BlogBundle\Entity\Tag")
	 * @ORM\JoinTable(name="blog_post_tag",
	 *     joinColumns={@ORM\JoinColumn(name="post_id", referencedColumnName="id")},
	 *     inverseJoinColumns={@ORM\JoinColumn(name="tag_id", referencedColumnName="id")})
	 */
	private $tags;


	/**
	 * @var File
	 *
	 * @ORM\ManyToOne(targetEntity="Octopouce\AdminBundle\Entity\File", cascade={"persist", "remove"})
	 */
	private $image;

	/**
	 * Post constructor.
	 */
	public function __construct() {
		$this->enabled     = true;
		$this->publishedAt = new \DateTime('now');
		$this->createdAt   = new \DateTime('now');
		$this->categories = new ArrayCollection();
		$this->tags = new ArrayCollection();
	}


	/**
	 * Get id
	 *
	 * @return int
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * Set title
	 *
	 * @param string $title
	 *
	 * @return Post
	 */
	public function setTitle($title)
	{
		$this->title = $title;

		return $this;
	}

	/**
	 * Get title
	 *
	 * @return string
	 */
	public function getTitle()
	{
		return $this->title;
	}

	/**
	 * Set slug
	 *
	 * @param string $slug
	 *
	 * @return Post
	 */
	public function setSlug($slug)
	{
		$this->slug = $slug;

		return $this;
	}

	/**
	 * Get slug
	 *
	 * @return string
	 */
	public function getSlug()
	{
		return $this->slug;
	}

	/**
	 * Set content
	 *
	 * @param string $content
	 *
	 * @return Post
	 */
	public function setContent($content)
	{
		$this->content = $content;

		return $this;
	}

	/**
	 * Get content
	 *
	 * @return string
	 */
	public function getContent()
	{
		return $this->content;
	}

	/**
	 * @return bool
	 */
	public function isEnabled(): bool {

		if($this->getPublishedAt() > new \DateTime()) {
			return false;
		}

		return $this->enabled;
	}

	/**
	 * @param bool $enabled
	 * @return Post
	 */
	public function setEnabled( bool $enabled ) {
		$this->enabled = $enabled;

		return $this;
	}

	/**
	 * @return \DateTime
	 */
	public function getPublishedAt(): \DateTime {
		return $this->publishedAt;
	}

	/**
	 * @param \DateTime $publishedAt
	 * @return Post
	 */
	public function setPublishedAt( \DateTime $publishedAt ) {
		$this->publishedAt = $publishedAt;

		return $this;
	}

	/**
	 * @return \DateTime
	 */
	public function getCreatedAt(): \DateTime {
		return $this->createdAt;
	}

	/**
	 * @param \DateTime $createdAt
	 * @return Post
	 */
	public function setCreatedAt( \DateTime $createdAt ) {
		$this->createdAt = $createdAt;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getSeoTitle() {
		return $this->seoTitle;
	}

	/**
	 * @param string $seoTitle
	 * @return Post
	 */
	public function setSeoTitle($seoTitle ) {
		$this->seoTitle = $seoTitle;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getSeoDescription() {
		return $this->seoDescription;
	}

	/**
	 * @param string $seoDescription
	 * @return Post
	 */
	public function setSeoDescription($seoDescription ) {
		$this->seoDescription = $seoDescription;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getOgTitle() {
		return $this->ogTitle;
	}

	/**
	 * @param string $ogTitle
	 * @return Post
	 */
	public function setOgTitle($ogTitle ) {
		$this->ogTitle = $ogTitle;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getOgDescription() {
		return $this->ogDescription;
	}

	/**
	 * @param string $ogDescription
	 * @return Post
	 */
	public function setOgDescription($ogDescription ) {
		$this->ogDescription = $ogDescription;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getOgImage() {
		return $this->ogImage;
	}

	/**
	 * @param string $ogImage
	 * @return Post
	 */
	public function setOgImage($ogImage ) {
		$this->ogImage = $ogImage;

		return $this;
	}


	/**
	 * @return User
	 */
	public function getUser() {
		return $this->user;
	}

	/**
	 * @param User $user
	 * @return Post
	 */
	public function setUser( User $user ) {
		$this->user = $user;

		return $this;
	}

	/**
	 * @return ArrayCollection
	 */
	public function getCategories() {
		return $this->categories;
	}

	/**
	 * Add category.
	 *
	 * @param Category $category
	 *
	 * @return Post
	 */
	public function addCategory(Category $category)
	{
		$this->categories[] = $category;

		return $this;
	}

	/**
	 * Remove category.
	 *
	 * @param Category $category
	 *
	 * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
	 */
	public function removeCategory(Category $category)
	{
		return $this->categories->removeElement($category);
	}

	/**
	 * @return ArrayCollection
	 */
	public function getTags() {
		return $this->tags;
	}

	/**
	 * Add tag.
	 *
	 * @param Category $tag
	 *
	 * @return Post
	 */
	public function addTag(Tag $tag)
	{
		$this->tags[] = $tag;

		return $this;
	}

	/**
	 * Remove tag.
	 *
	 * @param Tag $tag
	 *
	 * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
	 */
	public function removeTag(Tag $tag)
	{
		return $this->tags->removeElement($tag);
	}

	public function getImage(): ?File
	{
		return $this->image;
	}

	public function setImage( ?File $image ): self
	{
		$this->image = $image;

		return $this;
	}

	public function getThumbnail()
	{
		return $this->thumbnail;
	}

	public function setThumbnail($thumbnail): self
	{
		$this->thumbnail = $thumbnail;

		return $this;
	}
}
