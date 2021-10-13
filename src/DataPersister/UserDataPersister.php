<?php

namespace App\DataPersister;

use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserDataPersister implements DataPersisterInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private UserPasswordHasherInterface $passwordEncoder
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
        $this->entityManager->flush();
    }

    public function remove($data)
    {
        $this->entityManager->remove($data);
        $this->entityManager->flush();
    }

}