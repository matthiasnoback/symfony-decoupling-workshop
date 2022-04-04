<?php
declare(strict_types=1);

namespace App\DTO;

interface TaskDTORepositoryInterface
{
    /**
     * @return array<TaskDTO>
     */
    public function findAll(): array;
}
