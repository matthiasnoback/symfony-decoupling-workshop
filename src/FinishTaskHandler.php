<?php
declare(strict_types=1);

namespace App;

use App\Entity\Task;
use Doctrine\Persistence\ManagerRegistry;
use Psr\EventDispatcher\EventDispatcherInterface;

final class FinishTaskHandler
{
    public function __construct(
        private readonly ManagerRegistry $doctrine,
        private readonly EventDispatcherInterface $eventDispatcher,
    ) {
    }

    public function handle(FinishTask $command): void
    {
        $task = $this->doctrine->getManager()->find(
            Task::class,
            $command->taskId
        );
        if ($task === null) {
            throw new \RuntimeException('Could not find Task');
        }

        /** @var Task $task */
        $task->finish();
        $events = $task->releaseEvents();

        $this->doctrine->getManager()->flush();

        foreach ($events as $event) {
            $this->eventDispatcher->dispatch($event);
        }
    }
}
