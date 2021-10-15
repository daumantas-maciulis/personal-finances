<?php

namespace App\Repository;

use App\Entity\User;
use App\Entity\UserBalance;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method UserBalance|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserBalance|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserBalance[]    findAll()
 * @method UserBalance[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserBalanceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserBalance::class);
    }

    public function getLastBalanceRecord(User $user)
    {
        $qb = $this->createQueryBuilder('ub');

        $qb
            ->andWhere('ub.owner = :userId')
            ->setParameter('userId', $user->getId())
            ->orderBy('ub.dateCreated', 'DESC')
            ->setMaxResults(1)
        ;


        $result = $qb->getQuery()->getArrayResult();

        return $result[0];
    }
}
