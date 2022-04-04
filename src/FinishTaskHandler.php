<?php
declare(strict_types=1);

namespace App;

use App\Entity\Task;
use Doctrine\Persistence\ManagerRegistry;

final class FinishTaskHandler
{
    public function __construct(
        private readonly ManagerRegistry $doctrine
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

        $this->doctrine->getManager()->flush();
    }
}
