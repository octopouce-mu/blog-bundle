<?php
/**
 * Created by Kévin Hilairet <kevin@octopouce.mu>
 * Date: 01/06/2018
 */

namespace Octopouce\BlogBundle\Controller;

use Octopouce\BlogBundle\Entity\Post;
use Octopouce\BlogBundle\Form\PostType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/")
 */
class PostController extends Controller
{
	/**
	 * @Route("/", name="octopouce_blog_post_index")
	 */
	public function index() : Response {
		$posts = $this->getDoctrine()->getRepository(Post::class)->findByEnabled();

		return $this->render('@OctopouceBlog/Post/index.html.twig', [
			'posts' => $posts
		]);
	}

	/**
	 * @Route("/{slug}", name="octopouce_blog_post_show")
	 * @ParamConverter("post", class="OctopouceBlogBundle:Post")
	 */
	public function show(Post $post) : Response {

		if(!$post->isEnabled()){
			$this->denyAccessUnlessGranted(['ROLE_ADMIN', 'ROLE_BLOG'], null, 'Unable to access this page!');
		}

		return $this->render('@OctopouceBlog/Post/show.html.twig', [
			'post' => $post
		]);
	}
}