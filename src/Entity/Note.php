<?php
declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity()
 */
final class Note
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
    private string $note;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Task", inversedBy="notes")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\Type("App\Entity\Task")
     */
    private Task $task;

    public function setTask(Task $task): void
    {
        $this->task = $task;
    }

    public function getTask(): Task
    {
        return $this->task;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getNote(): string
    {
        return $this->note;
    }

    public function setNote(string $note): void
    {
        $this->note = $note;
    }
}
