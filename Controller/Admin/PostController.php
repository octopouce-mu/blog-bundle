<?php
/**
 * Created by Kévin Hilairet <kevin@octopouce.mu>
 * Date: 01/06/2018
 */

namespace Octopouce\BlogBundle\Controller\Admin;

use Octopouce\AdminBundle\Utils\FileUploader;
use Octopouce\BlogBundle\Entity\Post;
use Octopouce\BlogBundle\Form\PostType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/post")
 */
class PostController extends Controller
{
	/**
	 * @Route("/", name="octopouce_blog_admin_post_index")
	 */
	public function index() : Response {
		$posts = $this->getDoctrine()->getRepository(Post::class)->findBy([], ['createdAt' => 'DESC']);

		return $this->render('@OctopouceBlog/Admin/Post/index.html.twig', [
			'posts' => $posts
		]);
	}

	/**
	 * @Route("/{post}/show", name="octopouce_blog_admin_post_show")
	 */
	public function show(Post $post) : Response {

		return $this->render('@OctopouceBlog/Admin/Post/show.html.twig', [
			'post' => $post
		]);
	}

	/**
	 * @Route("/create", name="octopouce_blog_admin_post_create")
	 */
	public function create(Request $request, FileUploader $fileUploader) : Response {
		$em = $this->getDoctrine()->getManager();

		$post = new Post();
		$user = $this->getUser();
		$post->setUser($user);

		$form = $this->createForm(PostType::class, $post);

		$form->handleRequest($request);
		if ($form->isSubmitted() && $form->isValid()) {

			if($post->getImage()) {
				$nameImage = $fileUploader->upload($post->getImage()->getPath(), 'date', 'post-'.$post->getSlug());
				$post->getImage()->setPath($nameImage);
			}

			if($post->getThumbnail()) {
				$nameThumbnail = $fileUploader->upload($post->getThumbnail(), 'date', 'post-'.$post->getSlug().'-thumbnail');
				$post->setThumbnail($nameThumbnail);
			}

			$em->persist($post);
			$em->flush();

			return $this->redirectToRoute('octopouce_blog_admin_post_show', ['post'=>$post->getId()]);
		}

		return $this->render('@OctopouceBlog/Admin/Post/create.html.twig', [
			'form' => $form->createView()
		]);
	}

	/**
	 * @Route("/{post}/edit", name="octopouce_blog_admin_post_edit")
	 */
	public function edit(Post $post, Request $request, FileUploader $fileUploader) : Response {

		$form = $this->createForm(PostType::class, $post);

		$imgOld = $post->getImage();
		$oldThumbnail = $post->getThumbnail();


		$form->handleRequest($request);
		if ($form->isSubmitted() && $form->isValid()) {

			$fileSystem = new Filesystem();

			if($post->getThumbnail()) {
				if($oldThumbnail instanceof File) $fileSystem->remove($oldThumbnail);

				$nameThumbnail = $fileUploader->upload($post->getThumbnail(), 'date', 'post-'.$post->getSlug().'-thumbnail');
				$post->setThumbnail($nameThumbnail);
			} else {
				$post->setThumbnail($oldThumbnail instanceof File ? $oldThumbnail->getPathName() : $oldThumbnail);
			}

			if($post->getImage()) {
				if($imgOld && $imgOld->getPath() instanceof File) $fileSystem->remove($imgOld);

				$nameImage = $fileUploader->upload($post->getImage()->getPath(), 'date', 'post-'.$post->getSlug());
				$post->getImage()->setPath($nameImage);
			} else {
				$post->getImage()->setPath($imgOld->getPath() instanceof File ? $imgOld->getPath()->getPathName() : $imgOld->getPath());
			}

			$this->getDoctrine()->getManager()->flush();

			$this->addFlash('success', 'post.edited');

			return $this->redirectToRoute('octopouce_blog_admin_post_show', ['post' => $post->getId()]);
		}

		return $this->render('@OctopouceBlog/Admin/Post/edit.html.twig', [
			'post' => $post,
			'form' => $form->createView()
		]);
	}

	/**
	 * @Route("/{post}/delete", name="octopouce_blog_admin_post_delete")
	 */
	public function delete(Post $post) : Response {
		$em = $this->getDoctrine()->getManager();

		$fileSystem = new Filesystem();
		if($post->getImage()->getPath() instanceof File) {
			$fileSystem->remove($post->getImage()->getPath()->getPathName());
		}

		if($post->getThumbnail() instanceof File) {
			$fileSystem->remove($post->getThumbnail()->getPathName());
		}

		$em->remove($post);
		$em->flush();

		$this->addFlash('success', 'post.deleted');

		return $this->redirectToRoute('octopouce_blog_admin_post_index');
	}

	/**
	 * @Route("/api/upload/file", name="octopouce_blog_admin_post_api_upload_filed")
	 * @Method({"POST"})
	 */
	public function apiUploadFile(Request $request) : Response {
		var_dump($request->request);die;

		return $this->redirectToRoute('octopouce_blog_admin_post_index');
	}
}