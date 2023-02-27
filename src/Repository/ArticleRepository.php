<?php

namespace App\Repository;

use App\Entity\Article;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Service\FunctionService;


/**
 * @extends ServiceEntityRepository<Article>
 *
 * @method Article|null find($id, $lockMode = null, $lockVersion = null)
 * @method Article|null findOneBy(array $criteria, array $orderBy = null)
 * @method Article[]    findAll()
 * @method Article[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, FunctionService $FunctionService)
    {
        parent::__construct($registry, Article::class);
        $this->FunctionService = $FunctionService;
    }

    public function save(Article $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Article $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findLastFourArticlesExceptLatest(int $latestArticleId): array
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.id != :latestArticleId')
            ->orderBy('a.id', 'DESC')
            ->setMaxResults(8)
            ->setParameter('latestArticleId', $latestArticleId)
            ->getQuery()
            ->getResult();
    }

    public function findArticlesByAuthor(int $id, int $perPage, int $page)
    {

        $offset = ($page - 1) * $perPage;
        $offset = $this->FunctionService->nagitive_check($offset); //Ustawiamy dla paginatora bezwzględny positive number

        return $this->createQueryBuilder('a')
            ->leftJoin('a.Relation', 'c')
            ->andWhere('c.id = :author_id')
            ->setParameter('author_id', $id)
            ->orderBy('a.id', 'DESC')
            ->setMaxResults($perPage)
            ->setFirstResult($offset)
            ->getQuery()
            ->getResult();

    }

    public function findLastThreeTopWriter(){

        $lastMonday = new \DateTime('monday last week');
        $lastSunday = new \DateTime('sunday last week');

        return  $this->createQueryBuilder('a')
            ->select('au.name', 'au.id  AS userId', 'a.title', 'a.id', 'COUNT(au.name) AS articles_written')
            ->leftJoin('a.Relation', 'au')
            /* ->leftJoin('a.id', 'aa')*/
            ->andWhere('a.relaseDate BETWEEN :lastMonday AND :lastSunday')
            ->setParameter('lastMonday', $lastMonday)
            ->setParameter('lastSunday', $lastSunday)
            ->groupBy('au.id')
            ->orderBy('articles_written', 'DESC')
            ->setMaxResults(3)
            ->getQuery()
            ->getResult();


    }

//    /**
//     * @return Article[] Returns an array of Article objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('a.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Article
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
