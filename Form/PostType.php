<?php
/**
 * Created by Kévin Hilairet <kevin@octopouce.mu>
 * Date: 02/06/2018
 */

namespace Octopouce\BlogBundle\Form;

use App\Entity\Account\User;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use App\Entity\Category;
use Octopouce\AdminBundle\Entity\File;
use Octopouce\AdminBundle\Form\FileEntityType;
use Octopouce\AdminBundle\Form\Type\DateTimePickerType;
use Octopouce\AdminBundle\Form\Type\SwitchType;
use Octopouce\BlogBundle\Entity\Post;
use Octopouce\BlogBundle\Entity\Tag;
use Octopouce\BlogBundle\EventListener\PostFormListener;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PostType extends AbstractType
{
	/**
	 * @var EntityManagerInterface
	 */
	private $em;

	/**
	 * PostType constructor.
	 *
	 * @param EntityManagerInterface $em
	 */
	public function __construct( EntityManagerInterface $em ) {
		$this->em = $em;
	}


	public function buildForm(FormBuilderInterface $builder, array $options)
	{

		$builder
			->add('title', TextType::class)
			->add('slug', TextType::class, [
				'attr' => ['placeholder' => '']
			])
			->add('content', TextareaType::class, [
				'attr' => ['class' => 'editor'],
				'required' => false
			])

			->add('enabled', SwitchType::class, [
				'required' => false
			])
			->add('publishedAt', DateTimePickerType::class)

			->add('seoTitle', TextType::class, [
				'required' => false
			])
			->add('seoDescription', TextType::class, [
				'required' => false
			])

			->add('seoFacebookTitle', TextType::class, [
				'required' => false
			])
			->add('seoFacebookDescription', TextType::class, [
				'required' => false
			])
			->add('seoFacebookImage', FileType::class, [
				'required' => false
			])

			->add('seoTwitterTitle', TextType::class, [
				'required' => false
			])
			->add('seoTwitterDescription', TextType::class, [
				'required' => false
			])
			->add('seoTwitterImage', FileType::class, [
				'required' => false
			])

			->add('categories', EntityType::class,[
				'multiple' => true,
				'class' => Category::class,
				'query_builder' => function (EntityRepository $er) {
					return $er->createQueryBuilder('c')
						      ->where('c.type = :blog')
							  ->setParameter('blog', 'blog')
					          ->orderBy('c.name', 'ASC');
				},
				'required' => false,
				'attr' => ['data-increment' => 'true', 'class'=>'select2']
			])

			->add('tags', EntityType::class,[
				'multiple' => true,
				'class' => Tag::class,
				'query_builder' => function (EntityRepository $er) {
					return $er->createQueryBuilder('t')
					          ->orderBy('t.name', 'ASC');
				},
				'required' => false,
				'attr' => ['data-increment' => 'true', 'class'=>'select2']
			])

			->add('user', EntityType::class,[
				'multiple' => false,
				'class' => User::class,
				'required' => false,
				'placeholder' => 'Choose a user'
			])

			->add('image', FileEntityType::class,[
				'required' => false
			])

			->add('thumbnail', FileType::class,[
				'required' => false
			])

			->add('submit', SubmitType::class, [
				'label' => 'save',
			])
			->addEventSubscriber(new PostFormListener($this->em))
		;
	}

	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults([
			'data_class' => Post::class
		]);
	}
}