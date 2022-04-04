<?php
declare(strict_types=1);

namespace App;

final class FinishTask
{
    public function __construct(
        public readonly int $taskId
    ) {
    }
}
