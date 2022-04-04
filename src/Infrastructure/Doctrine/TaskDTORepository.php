<?php
declare(strict_types=1);

namespace App\Infrastructure\Doctrine;

use App\DTO\TaskDTO;
use App\DTO\TaskDTORepositoryInterface;
use App\Entity\Task;
use Doctrine\Persistence\ManagerRegistry;

final class TaskDTORepository implements TaskDTORepositoryInterface
{
    public function __construct(
        private readonly ManagerRegistry $doctrine
    ) {
    }

    public function findAll(): array
    {
        return array_map(
            fn(Task $task) => new TaskDTO(
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
