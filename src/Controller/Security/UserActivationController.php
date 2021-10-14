<?php

namespace App\Controller\Security;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserActivationController extends AbstractController
{
    /**
     * @Route("/confirmation/{confirmationHash}")
     */
    public function activateUser($confirmationHash, UserRepository $userRepository)
    {
        $userRepository->activateUser($confirmationHash);

        return $this->json('User was confirmed', Response::HTTP_CREATED);
    }
}