<?php
declare(strict_types=1);

namespace App\Infrastructure\Doctrine;

use App\Tasks\Application\TaskForList;
use App\DTO\TaskRepositoryInterface;
use App\Tasks\Domain\Model\Task\Task;
use Doctrine\Persistence\ManagerRegistry;

final class TaskRepository implements TaskRepositoryInterface
{
    public function __construct(
        private readonly ManagerRegistry $doctrine
    ) {
    }

    public function findAll(): array
    {
        return array_map(
            fn(Task $task) => new TaskForList(
                $task->getId(),
                $task->getTask(),
                $task->isFinished(),
                $task->getDueDate(),
            ),
            $this->doctrine->getRepository(Task::class)->findBy(
                [
                ],
                [
                    'dueDate' => 'ASC'
                ]
            )
        );
    }
}
