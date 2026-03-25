<?php

namespace App\Repository;

use App\Entity\Quest;
use App\Form\Model\QuestSearch;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Quest>
 */
class QuestRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Quest::class);
    }

    public function find6Last()
    {
        $qb = $this->createQueryBuilder('q');
        $qb->addOrderBy('q.startDateTime', 'DESC');
        //jointure + select
        $qb->leftJoin('q.status', 'status');
        $qb->addSelect('status');
        $qb->andWhere('status.label LIKE :label');
        $qb->setParameter('label', 'Ouverte');

        $query = $qb->getQuery();
        $query->setMaxResults(6);
        //permet de gérer la pagination sur jointure
        return $query->getResult();
    }

    public function findBySearch(QuestSearch $search)
    {
        $query = $this->createQueryBuilder('q');
        if ($search->getName()) {
            $query = $query
                ->andWhere('q.name LIKE :name')
                ->setParameter('name', '%' . $search->getName() . '%');
        }

        return $query->getQuery()->getResult();
    }

//    public function findQuestByPromoter()
//    {
//        $qb = $this->createQueryBuilder('q');
//        $qb->addOrderBy('q.promoter', 'DESC');
//        //jointure + select
//        $qb->leftJoin('q.status', 'status');
//        $qb->addSelect('status');
//
//        $query = $qb->getQuery();
//        //permet de gérer la pagination sur jointure
//        return $query->getResult();
//    }

    //    /**
    //     * @return Quest[] Returns an array of Quest objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('q')
    //            ->andWhere('q.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('q.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Quest
    //    {
    //        return $this->createQueryBuilder('q')
    //            ->andWhere('q.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
