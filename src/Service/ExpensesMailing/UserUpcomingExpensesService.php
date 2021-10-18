<?php

namespace App\Service\ExpensesMailing;

use App\Entity\Expenses;
use App\Entity\User;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Mailer\MailerInterface;

class UserUpcomingExpensesService
{
    public function __construct(
        private MailerInterface $mailer,
        private KernelInterface $kernel
    ){}

    public function sendUpcomingExpenses(User $user, array $expenses)
    {
        $projectRoot = $this->kernel->getProjectDir();
        $emailTemplate = $projectRoot . '/templates/emails/upcomingExpenses.html.twig';

        $email = (new TemplatedEmail())
            ->from('system@finances.com')
            ->to($user->getEmail())
            ->subject('Your upcoming payments')
            ->text($this->createMailText($user, $expenses))
            ->htmlTemplate('emails/upcomingExpenses.html.twig')
            ->context([
                'user' => $user,
                'expenses' => $expenses
            ])
            ;
        $this->mailer->send($email);
    }

    private function createMailText(User $user, array $expenses)
    {
        $paymentSum = 0;

        /** @var  User $user */
        $firstName = $user->getFirstName();
        $message = sprintf("Dear %s ,
        Your upcoming payments are: 
        ", $firstName);

        $payments = '';

        foreach ($expenses as $expense) {
            $dueDate = $expense['dueDate'];
            /** @var Expenses $expense */
            $payments .= sprintf('
            Payment title: %s,
            Payments quantity: %s,
            Due date: %s
            
            ', $expense['title'], $expense['quantity'], $dueDate->format('Y-m-d'));

            $paymentSum += $expense['quantity'];
        }

        $fullMessage = $message . $payments . sprintf('
        All payments sum is: %d Euros.
        ', $paymentSum);

        return $fullMessage;
    }
}