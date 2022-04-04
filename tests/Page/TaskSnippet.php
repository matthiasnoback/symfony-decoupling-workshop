<?php
declare(strict_types=1);

namespace App\Tests\Page;

use PHPUnit\Framework\Assert;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\DomCrawler\Crawler;

final class TaskSnippet
{
    public function __construct(
        private KernelBrowser $client,
        private Crawler $crawler
    ) {
        Assert::assertStringContainsString('task', $this->crawler->attr('class'));
    }

    public function nameContains(string $needle): bool
    {
        return str_contains($this->name(), $needle);
    }

    public function name(): string
    {
        return $this->crawler->filter('.task-task')->text();
    }

    private function dueDate(): string
    {
        return $this->crawler->filter('.task-due-date')->text() ?? '';
    }

    public function goToEditPage(): TaskEditPage
    {
        return new TaskEditPage(
            $this->client,
            $this->client->clickLink('Edit')
        );
    }

    public function finish(): void
    {
        $this->client->submitForm('Finish');
    }

    public function assertDueDateIs(int $year, int $month, int $day): void
    {
        Assert::assertStringContainsString(sprintf('%04d-%02d-%02d', $year, $month, $day), $this->dueDate());
    }

    public function show(): ShowPage
    {
        return new ShowPage(
            $this->client,
            $this->client->click($this->crawler->filter('a[aria-label="Show"]')->first()->link())
        );
    }

    public function assertIsOverdue(): void
    {
        Assert::assertStringContainsString('Overdue', $this->dueDate());
    }
}
