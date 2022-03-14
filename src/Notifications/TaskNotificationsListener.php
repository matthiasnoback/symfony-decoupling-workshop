<?php
declare(strict_types=1);

namespace App\Notifications;

use App\Entity\Task;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

final class TaskNotificationsListener
{
    public function __construct(private MailerInterface $mailer)
    {
    }

    public function preUpdate(PreUpdateEventArgs $event): void
    {
        $task = $event->getObject();
        if (!$task instanceof Task) {
            return;
        }

        if ($task->getAssignedTo() === null) {
            return;
        }

        if ($event->hasChangedField('dueDate')) {
            $this->mailer->send(
                (new Email())
                    ->subject('Re: ' . $task->getTask())
                    ->text('The due date of this task has changed')
                    ->from('no-reply@example.com')
                    ->to($task->getAssignedTo()->getEmailAddress())
            );
        }
    }
}
