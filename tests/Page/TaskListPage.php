<?php
declare(strict_types=1);

namespace App\Tests\Page;

use PHPUnit\Framework\Assert;
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
        foreach ($this->crawler->filter('.task') as $node) {
            $taskCrawler = new Crawler($node, $this->crawler->getUri());
            if (str_contains($taskCrawler->filter('.task-task')->text(), $name)) {
                return new TaskSnippet($this->client, $taskCrawler);
            }
        }

        throw new \RuntimeException('Did not find a task with name ' . $name);
    }
}
