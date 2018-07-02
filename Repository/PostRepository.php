<?php

namespace Octopouce\BlogBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\Query;
use Octopouce\BlogBundle\Entity\Post;

class PostRepository extends ServiceEntityRepository
{
	public function __construct(ManagerRegistry $registry)
	{
		parent::__construct($registry, Post::class);
	}

	public function findByEnabled(){
		$qb = $this->createQueryBuilder('b');

		$now = new \DateTime();
		$qb->where('b.enabled = :enabled')
			->setParameter('enabled', true)
			->andWhere('b.publishedAt <= :now')
			->setParameter('now', $now)
			->orderBy('b.publishedAt', 'desc');

		return $qb->getQuery()->getResult();
	}
}
