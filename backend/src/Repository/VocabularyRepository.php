<?php

namespace App\Repository;

use App\Entity\Vocabulary;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Vocabulary>
 *
 * @method Vocabulary|null find($id, $lockMode = null, $lockVersion = null)
 * @method Vocabulary|null findOneBy(array $criteria, array $orderBy = null)
 * @method Vocabulary[]    findAll()
 * @method Vocabulary[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VocabularyRepository extends AbstractRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Vocabulary::class, 'vocabulary_entity');
    }

    public function queryAll(QueryBuilder $qb, ?array $search)
    {
        $qb = $this->addFilterSearch($qb, $search);
        $qb = $this->addLogSuppFilter($qb, $search);

        return $qb;
    }

    public function add(Vocabulary $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Vocabulary $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return Vocabulary[] Returns an array of Vocabulary objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('v')
//            ->andWhere('v.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('v.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Vocabulary
//    {
//        return $this->createQueryBuilder('v')
//            ->andWhere('v.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
