<?php
declare(strict_types=1);

namespace App\Tests\Page;

use PHPUnit\Framework\Assert;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\DomCrawler\Crawler;

final class AddNotePage
{
    private array $formData = [];

    public function __construct(
        private KernelBrowser $client,
        Crawler $crawler
    ) {
        Assert::assertStringContainsString('task_add_note', $crawler->filter('body')->attr('class'));
    }

    public function setNote(string $note): self
    {
        $this->formData['note[note]'] = $note;

        return $this;
    }

    public function submit(): void
    {
        $this->client->submitForm('Add note', $this->formData);
    }
}
