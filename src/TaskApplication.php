<?php
declare(strict_types=1);

namespace App;

final class TaskApplication implements TaskInterface
{
    public function __construct(
        private readonly FinishTaskHandler $finishTaskHandler
    ) {
    }

    public function finishTask(FinishTask $command): void
    {
        $this->finishTaskHandler->handle($command);
    }
}
