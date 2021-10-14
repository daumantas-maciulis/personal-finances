<?php

namespace App\Service\Security;

use App\Entity\User;
use App\Exception\UserIsNotActivatedException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserChecker implements UserCheckerInterface
{
    public function checkPreAuth(UserInterface $user): void
    {
        if (!$user instanceof User) {
            return;
        }

        if(!$user->getActivated()) {
            throw new UserIsNotActivatedException('Your user is not activated. Please check your email');
        }
    }

    public function checkPostAuth(UserInterface $user): void
    {
        if(!$user instanceof User) {
            return;
        }
    }

}