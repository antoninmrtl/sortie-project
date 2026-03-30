<?php

namespace App\Repository;

use App\Entity\Quest;
use App\Entity\User;
use App\Form\Model\QuestSearch;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Quest>
 */
class QuestRepository extends ServiceEntityRepository
{

    public function __construct(ManagerRegistry $registry, private StatusRepository $statusRepository)
    {
        parent::__construct($registry, Quest::class);
    }

    public function find6Last()
    {
        $qb = $this->createQueryBuilder('q');
        $qb->addOrderBy('q.startDateTime', 'DESC');
        $qb->leftJoin('q.status', 'status')->addSelect('status');
        $qb->leftJoin('q.promoter', 'p')->addSelect('p');
        $qb->leftJoin('q.users', 'u')->addSelect('u');
        $qb->andWhere('status.label LIKE :label');
        $qb->setParameter('label', 'Ouverte');

        $query = $qb->getQuery();
        $query->setMaxResults(6);
        return $query->getResult();
    }

    public function findBySearch(QuestSearch $search, ?User $user)
    {

        $archiveStatus = $this->statusRepository->findOneBy(['label' => 'Archive']);
        $creationStatus = $this->statusRepository->findOneBy(['label' => 'En création']);

        $query = $this->createQueryBuilder('q')
            ->leftJoin('q.status', 's')->addSelect('s')
            ->leftJoin('q.promoter', 'p')->addSelect('p')
            ->leftJoin('q.users', 'u')->addSelect('u')
            ->leftJoin('q.place', 'pl')->addSelect('pl')
            ->andWhere('s != :archive and s != :creation')
            ->setParameter('archive', $archiveStatus)
            ->setParameter('creation', $creationStatus);

        if ($search->getName()) {
            $query = $query
                ->andWhere('q.name LIKE :name')
                ->setParameter('name', '%' . $search->getName() . '%');
        }
        if ($user) {
            if ($search->isPromoter()) {
                $query = $query
                    ->andWhere('q.promoter = :user')
                    ->setParameter('user', $user);
            }

            if ($search->isRegistered()) {
                $query = $query
                    ->innerJoin('q.users', 'uu')
                    ->andWhere('uu.id = :userId')
                    ->setParameter('userId', $user->getId());
            }

            if ($search->startDate) {
                $query->andWhere('q.startDateTime >= :start')
                    ->setParameter('start', $search->startDate);
            }

            if ($search->endDate) {
                $query->andWhere('q.startDateTime <= :end')
                    ->setParameter('end', $search->endDate);
            }
        }

        return $query->getQuery()->getResult();
    }

    public function findAllClean()
    {

        $query = $this->createQueryBuilder('q')
            ->leftJoin('q.status', 's')->addSelect('s')
            ->leftJoin('q.promoter', 'p')->addSelect('p')
            ->leftJoin('q.users', 'u')->addSelect('u');


        return $query->getQuery()->getResult();
    }

    public function findAllArchive()
    {

        $archiveStatus = $this->statusRepository->findOneBy(['label' => 'Archive']);


        $query = $this->createQueryBuilder('q')
            ->leftJoin('q.status', 's')->addSelect('s')
            ->leftJoin('q.promoter', 'p')->addSelect('p')
            ->leftJoin('q.users', 'u')->addSelect('u')
            ->andWhere('s = :archive')
            ->setParameter('archive', $archiveStatus);



        return $query->getQuery()->getResult();
    }

    public function findAllCreateByPromoter(User $user)
    {

        $creationStatus = $this->statusRepository->findOneBy(['label' => 'En création']);


        $query = $this->createQueryBuilder('q')
            ->leftJoin('q.status', 's')->addSelect('s')
            ->leftJoin('q.promoter', 'p')->addSelect('p')
            ->leftJoin('q.users', 'u')->addSelect('u')
            ->andWhere('s = :creation')
            ->setParameter('creation', $creationStatus)
            ->andWhere('q.promoter = :promoter')
            ->setParameter('promoter', $user);



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
