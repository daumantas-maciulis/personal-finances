<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class testController extends AbstractController
{
    /**
     * @Route("/test")
     */
    public function index()
    {
        return $this->render('./emails/upcomingExpenses.html.twig');
    }
}