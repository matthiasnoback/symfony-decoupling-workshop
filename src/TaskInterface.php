<?php
declare(strict_types=1);

namespace App;

/**
 * Everything you can do with this app in the "task domain"
 */
interface TaskInterface
{
    // public function createTask(): TaskId

    public function finishTask(FinishTask $command): void;
}
