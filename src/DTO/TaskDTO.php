<?php
declare(strict_types=1);

namespace App\DTO;

final class TaskDTO
{
    public function __construct(
        public readonly int $id,
        public readonly string $task,
        public readonly bool $finished,
        public readonly ?\DateTime $dueDate,
    )
    {
    }
}
