<?php
/**
 * Created by Kévin Hilairet <kevin@octopouce.mu>
 * Date: 01/06/2018
 */

namespace Octopouce\BlogBundle\Controller\Admin;

use Octopouce\BlogBundle\Entity\Tag;
use Octopouce\BlogBundle\Form\TagType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/tag")
 * @IsGranted("ROLE_BLOG")
 */
class TagController extends Controller
{
	/**
	 * @Route("/", name="octopouce_blog_admin_tag_index")
	 */
	public function index() : Response {
		$tags = $this->getDoctrine()->getRepository(Tag::class)->findAll();

		return $this->render('@OctopouceBlog/Admin/Tag/index.html.twig', [
			'tags' => $tags
		]);
	}

	/**
	 * @Route("/create", name="octopouce_blog_admin_tag_create")
	 */
	public function create(Request $request) : Response {
		$em = $this->getDoctrine()->getManager();

		$tag = new Tag();

		$form = $this->createForm(TagType::class, $tag);

		$form->handleRequest($request);
		if ($form->isSubmitted() && $form->isValid()) {

			$em->persist($tag);
			$em->flush();

			return $this->redirectToRoute('octopouce_blog_admin_tag_edit', ['tag'=>$tag->getId()]);
		}

		return $this->render('@OctopouceBlog/Admin/Tag/create.html.twig', [
			'form' => $form->createView()
		]);
	}

	/**
	 * @Route("/{tag}/edit", name="octopouce_blog_admin_tag_edit")
	 */
	public function edit(Tag $tag, Request $request) : Response {

		$form = $this->createForm(TagType::class, $tag);

		$form->handleRequest($request);
		if ($form->isSubmitted() && $form->isValid()) {

			$this->getDoctrine()->getManager()->flush();

			$this->addFlash('success', 'tag.edited');

			return $this->redirectToRoute('octopouce_blog_admin_tag_edit', ['tag' => $tag->getId()]);
		}

		return $this->render('@OctopouceBlog/Admin/Tag/edit.html.twig', [
			'tag' => $tag,
			'form' => $form->createView()
		]);
	}

	/**
	 * @Route("/{tag}/delete", name="octopouce_blog_admin_tag_delete")
	 */
	public function delete(Tag $tag) : Response {
		$em = $this->getDoctrine()->getManager();
		$em->remove($tag);
		$em->flush();

		$this->addFlash('success', 'tag.deleted');

		return $this->redirectToRoute('octopouce_blog_admin_tag_index');
	}
}