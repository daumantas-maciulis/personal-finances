<?php

namespace App\DataProvider;

use ApiPlatform\Core\DataProvider\ContextAwareCollectionDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use App\Dto\Output\UserBalance\UserBalanceReportOutput;
use App\Entity\User;
use App\Entity\UserBalance;
use App\Repository\UserBalanceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Security\Core\Security;

class UserBalanceReportDataProvider implements ContextAwareCollectionDataProviderInterface, RestrictedDataProviderInterface
{
    public function __construct(
        private Security              $security,
        private UserBalanceRepository $repository
    )
    {
    }

    public function getCollection(string $resourceClass, string $operationName = null, array $context = [])
    {
        /** @var User $user*/
        $user = $this->security->getUser();
        $userRecords = $this->repository->getUserBalanceRecords($user);

        $response = new ArrayCollection();

        foreach ($userRecords as $record) {
            /** @var UserBalance $record */
            $op = new UserBalanceReportOutput();
            $op->setId($userRecords[0]['id']);
            $op->setTitle($userRecords[0]['title']);
            $op->setDescription($userRecords[0]['description']);
            $op->setCurrentValue($userRecords[0]['currentValue']);
            $op->setTransactionValue($userRecords[0]['transactionValue']);
            $op->setIsIncome($userRecords[0]['isIncome']);

            $response->add($op);
        }

        return $response;
    }

    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        return UserBalance::class === $resourceClass;
    }
}