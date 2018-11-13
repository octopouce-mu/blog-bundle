<?php
/**
 * Created by Kévin Hilairet <kevin@octopouce.mu>
 * Date: 01/06/2018
 */

namespace Octopouce\BlogBundle\Controller\Admin;

use Octopouce\AdminBundle\Entity\Category;
use Octopouce\BlogBundle\Form\CategoryType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/category")
 * @IsGranted("ROLE_BLOG")
 */
class CategoryController extends Controller
{
	/**
	 * @Route("/", name="octopouce_blog_admin_category_index")
	 */
	public function index() : Response {
		$categories = $this->getDoctrine()->getRepository(Category::class)->findBy(['type' => 'blog']);

		return $this->render('@OctopouceBlog/Admin/Category/index.html.twig', [
			'categories' => $categories
		]);
	}

	/**
	 * @Route("/create", name="octopouce_blog_admin_category_create")
	 */
	public function create(Request $request) : Response {
		$em = $this->getDoctrine()->getManager();

		$category = new Category();
		$category->setType('blog');

		$form = $this->createForm(CategoryType::class, $category);

		$form->handleRequest($request);
		if ($form->isSubmitted() && $form->isValid()) {

			$em->persist($category);
			$em->flush();

			return $this->redirectToRoute('octopouce_blog_admin_category_edit', ['category'=>$category->getId()]);
		}

		return $this->render('@OctopouceBlog/Admin/Category/create.html.twig', [
			'form' => $form->createView()
		]);
	}

	/**
	 * @Route("/{category}/edit", name="octopouce_blog_admin_category_edit")
	 */
	public function edit(Category $category, Request $request) : Response {

		$form = $this->createForm(CategoryType::class, $category);

		$form->handleRequest($request);
		if ($form->isSubmitted() && $form->isValid()) {

			$this->getDoctrine()->getManager()->flush();

			$this->addFlash('success', 'category.edited');

			return $this->redirectToRoute('octopouce_blog_admin_category_edit', ['category' => $category->getId()]);
		}

		return $this->render('@OctopouceBlog/Admin/Category/edit.html.twig', [
			'category' => $category,
			'form' => $form->createView()
		]);
	}

	/**
	 * @Route("/{category}/delete", name="octopouce_blog_admin_category_delete")
	 */
	public function delete(Category $category) : Response {
		$em = $this->getDoctrine()->getManager();
		$em->remove($category);
		$em->flush();

		$this->addFlash('success', 'category.deleted');

		return $this->redirectToRoute('octopouce_blog_admin_category_index');
	}
}