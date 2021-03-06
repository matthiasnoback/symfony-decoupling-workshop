<?php
declare(strict_types=1);

namespace App\Entity;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Note", mappedBy="task", cascade={"persist"})
     */
    private Collection $notes;

    /**
     * @ORM\ManyToOne (targetEntity="App\Entity\User")
     */
    private ?User $assignedTo = null;

    /**
     * @ORM\Column(type="boolean")
     */
    private bool $isFinished = false;

    public function __construct()
    {
        $this->notes = new ArrayCollection();
    }

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

    public function getNotes(): Collection
    {
        return $this->notes;
    }

    public function setAssignedTo(User $user): void
    {
        $this->assignedTo = $user;
    }

    public function getAssignedTo(): ?User
    {
        return $this->assignedTo;
    }

    public function setIsFinished(bool $isFinished): void
    {
        $this->isFinished = $isFinished;
    }

    public function isFinished(): bool
    {
        return $this->isFinished;
    }
}
