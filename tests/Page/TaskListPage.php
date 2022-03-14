<?php
declare(strict_types=1);

namespace App\Tests\Page;

use PHPUnit\Framework\Assert;
use RuntimeException;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\DomCrawler\Crawler;

final class TaskListPage
{
    public function __construct(
        private KernelBrowser $client,
        private Crawler $crawler
    ) {
        Assert::assertStringContainsString('task_list', $crawler->filter('body')->attr('class'));
    }

    public function taskWithName(string $name): TaskSnippet
    {
        foreach ($this->tasks() as $task) {
            if ($task->nameContains($name)) {
                return $task;
            }
        }

        throw new RuntimeException('Did not find a task with name ' . $name);
    }

    public function assertTaskNotExists(string $name): void
    {
        foreach ($this->tasks() as $task) {
            if ($task->nameContains($name)) {
                throw new RuntimeException('Did not expect to find this task in the list');
            }
        }
    }

    /**
     * @return array<TaskSnippet>
     */
    private function tasks(): array
    {
        $tasks = [];

        foreach ($this->crawler->filter('.task') as $node) {
            $tasks[] = new TaskSnippet(
                $this->client,
                new Crawler($node, $this->crawler->getUri())
            );
        }

        return $tasks;
    }

    public function firstTask(): TaskSnippet
    {
        $tasks = $this->tasks();
        Assert::assertArrayHasKey(0, $tasks, 'Expected to find at least one task');

        return $tasks[0];
    }
}
