<?php

namespace App\Repository;

use App\Entity\Expenses;
use App\Entity\User;
use App\Exception\BadDateInputException;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Expenses|null find($id, $lockMode = null, $lockVersion = null)
 * @method Expenses|null findOneBy(array $criteria, array $orderBy = null)
 * @method Expenses[]    findAll()
 * @method Expenses[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ExpensesRepository extends ServiceEntityRepository
{
    public function __construct(
        ManagerRegistry $registry,
    )
    {
        parent::__construct($registry, Expenses::class);
    }

    public function getUpcomingPayments(User $user, string $date)
    {
        $qb = $this->createQueryBuilder('e');

        $qb
            ->andWhere('e.owner = :userId')
            ->andWhere('e.dueDate < :date')
            ->setParameter('userId', $user->getId())
            ->setParameter('date', $date)
            ->orderBy('e.dueDate', 'ASC')
        ;

        return $qb->getQuery()->getArrayResult();
    }

}
