<?php
declare(strict_types=1);

namespace App\Tasks\Application;

use App\DTO\TaskRepositoryInterface;
use App\Tasks\Application\FinishTask;
use App\Tasks\Application\FinishTaskHandler;
use App\Tasks\Application\TasksInterface;

final class TasksApplication implements TasksInterface
{
    public function __construct(
        private readonly FinishTaskHandler $finishTaskHandler,
        private readonly TaskRepositoryInterface $taskRepository
    ) {
    }

    public function finishTask(FinishTask $command): void
    {
        $this->finishTaskHandler->handle($command);
    }

    public function findAllTasks(): array
    {
        return $this->taskRepository->findAll();
    }

    public function addNote(string $taskId, string $note): void
    {
        // TODO: Implement addNote() method.
    }
}
