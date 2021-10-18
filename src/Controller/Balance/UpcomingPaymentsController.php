<?php

namespace App\Controller\Balance;

use App\Entity\User;
use App\Repository\ExpensesRepository;
use App\Service\ExpensesMailing\UserUpcomingExpensesService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Security;

class UpcomingPaymentsController extends AbstractController
{
    public function __construct(
        private Security $security,
        private ExpensesRepository $expensesRepository,
        private UserUpcomingExpensesService $service
    ){}

    public function __invoke()
    {
        /** @var User $user */
        $user = $this->security->getUser();

        $firstDayOfNextMonth = (new \DateTimeImmutable('first day of next month'))->format('Y-m-d');

        $payments = $this->expensesRepository->getUpcomingPayments($user, $firstDayOfNextMonth);

        $this->service->sendUpcomingExpenses($user, $payments);

    }
}