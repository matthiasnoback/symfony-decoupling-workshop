<?php
declare(strict_types=1);

namespace App\Tasks\Application;

use App\Tasks\Application\FinishTask;

/**
 * Everything you can do with this app in the "task domain"
 */
interface TasksInterface
{
    // public function createTask(): TaskId

    public function finishTask(FinishTask $command): void;

    /**
     * @return array<TaskForList>
     */
    public function findAllTasks(): array;

    public function addNote(string $taskId, string $note): void;
}
