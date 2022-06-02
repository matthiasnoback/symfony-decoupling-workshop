<?php
declare(strict_types=1);

namespace App\Tasks\Domain\Model\Task;

use App\Tasks\Domain\Model\Task\TaskWasAlreadyFinished;
use App\Domain\Model\Task\TaskWasFinished;
use App\Entity\User;
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

    /**
     * @var array<object>
     */
    private array $events = [];

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

    public function isFinished(): bool
    {
        return $this->isFinished;
    }

    public function finish(): void
    {
        if ($this->isFinished()) {
            $this->events[] = new TaskWasAlreadyFinished();

            return;
        }

        $this->events[] = new TaskWasFinished();

        $this->isFinished = true;
    }

    public function releaseEvents(): array
    {
        $events = $this->events;

        $this->events = [];

        return $events;
    }
}
