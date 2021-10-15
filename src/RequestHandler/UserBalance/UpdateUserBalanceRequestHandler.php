<?php

namespace App\RequestHandler\UserBalance;

use App\Dto\Input\UserBalance\UpdateUserBalanceRequest;
use App\Entity\User;
use App\Entity\UserBalance;
use App\Repository\UserBalanceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Security\Core\Security;

class UpdateUserBalanceRequestHandler implements MessageHandlerInterface
{
    public function __construct(
        private EntityManagerInterface $manager,
        private Security               $security,
        private UserBalanceRepository  $repository
    )
    {
    }

    public function __invoke(UpdateUserBalanceRequest $balanceRequest): UserBalance
    {
        /** @var User $user */
        $user = $this->security->getUser();
        /** @var UserBalance $lastRecord */
        $lastRecord = $this->repository->getLastBalanceRecord($user);

        $newBalance = new UserBalance();

        $newBalance->setTitle($balanceRequest->getTitle());

        if ($balanceRequest->getDescription()) {
            $newBalance->setDescription($balanceRequest->getDescription());
        } else {
            $newBalance->setDescription(null);
        }

        $newValue = $lastRecord['currentValue'] + $balanceRequest->getValue();

        $newBalance->setPreviousValue($lastRecord['currentValue']);
        if ($balanceRequest->getValue() > 0) {
            $newBalance->setIsIncome(true);
        } else {
            $newBalance->setIsIncome(false);
        }
        $newBalance->setTransactionValue($balanceRequest->getValue());
        $newBalance->setCurrentValue($newValue);
        $newBalance->setOwner($user);

        $this->manager->persist($newBalance);
        $this->manager->flush();

        return $newBalance;
    }
}