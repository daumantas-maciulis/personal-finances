<?php

namespace App\DataPersister;

use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use App\Entity\User;
use App\Entity\UserBalance;
use App\Service\UserConfirmationEmailService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserDataPersister implements DataPersisterInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private UserPasswordHasherInterface $passwordEncoder,
        private UserConfirmationEmailService $userConfirmationEmailService
    ){}

    public function supports($data): bool
    {
        return $data instanceof User;
    }

    public function persist($data)
    {
        /** @var User $data */
        if($data->getPassword()) {
            $data->setPassword(
                $this->passwordEncoder->hashPassword($data, $data->getPassword())
            );
        }
        $this->entityManager->persist($data);

        $userBalance = new UserBalance();
        $userBalance->setTitle('Initial balance');
        $userBalance->setPreviousValue(0);
        $userBalance->setCurrentValue(0);
        $userBalance->setIsIncome(true);
        $userBalance->setOwner($data);

        $this->entityManager->persist($userBalance);

        $this->entityManager->flush();

        $this->userConfirmationEmailService->sendConfirmationEmail($data);
    }

    public function remove($data)
    {
        $this->entityManager->remove($data);
        $this->entityManager->flush();
    }

}