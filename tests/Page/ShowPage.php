<?php
declare(strict_types=1);

namespace App\Tests\Page;

use PHPUnit\Framework\Assert;
use RuntimeException;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\DomCrawler\Crawler;

final class ShowPage
{
    public function __construct(
        private KernelBrowser $client,
        private Crawler $crawler
    ) {
        Assert::assertStringContainsString('task_show', $crawler->filter('body')->attr('class'));
    }

    public function assertNoteExists(string $expectedNote): void
    {
        foreach ($this->crawler->filter('.task-note') as $noteNode) {
            if (str_contains($noteNode->textContent, $expectedNote)) {
                return;
            }
        }

        throw new RuntimeException('Note not found');
    }

    public function addNote(): AddNotePage
    {
        return new AddNotePage(
            $this->client,
            $this->client->clickLink('Add a note')
        );
    }
}
