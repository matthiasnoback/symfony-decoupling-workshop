<?php
declare(strict_types=1);

namespace App\DTO;

use App\Tasks\Application\TaskForList;

interface TaskRepositoryInterface
{
    /**
     * @return array<TaskForList>
     */
    public function findAll(): array;
}
