<?php

namespace App\DataFixtures;

use App\Entity\Expenses;
use App\Entity\User;
use App\Service\UserPasswordService;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{
    public function __construct(
        private UserPasswordService $passwordService
    )
    {
    }

    public function load(ObjectManager $manager)
    {
        foreach ($this->loadUsers() as $userData) {
            $user = new User();

            $user->setFirstName($userData['firstName']);
            $user->setLastName($userData['lastName']);
            $user->setEmail($userData['email']);
            $user->setPassword(
                $this->passwordService->hashUserPassword($user, $userData['password'])
            );
            $user->setRoles($userData['roles']);
            $user->setActivated(true);
            $manager->persist($user);

            $faker = Factory::create();

            for ($i = 0; $i < 10; $i++) {
                $expense = new Expenses();
                $expense->setTitle($faker->title);
                $expense->setQuantity($faker->numberBetween(1, 100000));
                $expense->setDueDate($faker->dateTimeBetween('+1 days', '+20 days'));
                $expense->setOwner($user);

                $manager->persist($expense);
            }
        }

        $manager->flush();
    }

    private function loadUsers(): array
    {
        return [
            [
                'firstName' => 'Pirmas',
                'lastName' => 'Pirmavicius',
                'email' => 'admin@admin.com',
                'roles' => ['ROLE_USER', 'ROLE_ADMIN'],
                'password' => 'labas'
            ],
            [
                'firstName' => 'Antras',
                'lastName' => 'Antranavicius',
                'email' => 'user@user.com',
                'roles' => ['ROLE_USER'],
                'password' => 'labas'
            ]
        ];
    }

}