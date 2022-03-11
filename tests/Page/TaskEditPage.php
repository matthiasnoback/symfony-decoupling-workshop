<?php
declare(strict_types=1);

namespace App\Tests\Page;

use PHPUnit\Framework\Assert;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\DomCrawler\Crawler;

final class TaskEditPage
{
    private array $formData = [];

    public function __construct(
        private KernelBrowser $client,
        private Crawler $crawler
    ) {
        Assert::assertStringContainsString('task_edit', $crawler->filter('body')->attr('class'));
    }

    public function setTask(string $task): self
    {
        $this->formData['task[task]'] = $task;

        return $this;
    }

    public function setDueDate(int $year, int $month, int $day): self
    {
        $this->formData['task[dueDate][year]'] = $year;
        $this->formData['task[dueDate][month]'] = $month;
        $this->formData['task[dueDate][day]'] = $day;

        return $this;
    }

    public function submit(): TaskListPage
    {
        return new TaskListPage($this->client, $this->client->submitForm('Save', $this->formData));
    }
}
