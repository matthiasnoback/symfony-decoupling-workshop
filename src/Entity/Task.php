<?php
declare(strict_types=1);

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity()
 */
class Task
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id()
     * @ORM\GeneratedValue()
     */
    private int $id;

    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank
     */
    private string $task;

    /**
     * @ORM\Column(type="date")
     * @Assert\NotBlank
     * @Assert\Type("DateTime")
     */
    private ?DateTime $dueDate;

    public function getId(): int
    {
        return $this->id;
    }

    public function getTask(): string
    {
        return $this->task;
    }

    public function setTask(string $task): void
    {
        $this->task = $task;
    }

    public function getDueDate(): ?DateTime
    {
        return $this->dueDate;
    }

    public function setDueDate(?DateTime $dueDate): void
    {
        $this->dueDate = $dueDate;
    }

    public function assigneeEmailAddress(): string
    {
        // TODO take from User $assignee
        return 'user@example.com';
    }
}
