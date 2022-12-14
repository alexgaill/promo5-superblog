<?php

namespace App\Repository;

use App\Entity\Post;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Post>
 *
 * @method Post|null find($id, $lockMode = null, $lockVersion = null)
 * @method Post|null findOneBy(array $criteria, array $orderBy = null)
 * @method Post[]    findAll()
 * @method Post[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Post::class);
    }

    public function add(Post $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Post $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }


    public function search(?string $search): array
    {
        $qb = $this->createQueryBuilder('p');
        if ($search) {
            // $qb->expr()->like('u.firstname', $qb->expr()->literal('Gui%'))
            // $qb->orWhere("p.title LIKE %$search%")
            // ->orWhere("p.content LIKE %$search%")
            $qb->innerJoin('p.category', 'c');
            $qb->orWhere($qb->expr()->like('p.title', $qb->expr()->literal('%'. $search .'%')));
            $qb->orWhere($qb->expr()->like('p.content', $qb->expr()->literal('%'. $search .'%')));
            $qb->orWhere($qb->expr()->like('c.title', $qb->expr()->literal('%'. $search .'%')));
            // ->setParameter('search', $search)
        }
        return $qb->getQuery()
        ->getResult()
        ;
    }


    public function pagination (int $page, int $limit): array
    {
        return $this->createQueryBuilder('p')
        ->setMaxResults($limit)
        ->setFirstResult(1 + $limit * ($page -1))
        ->getQuery()
        ->getResult()
        ;
    }
//    /**
//     * @return Post[] Returns an array of Post objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Post
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
