<?php
declare(strict_types=1);

namespace App\Tasks\Application;

final class FinishTask
{
    public function __construct(
        public readonly int $taskId
    ) {
    }
}
