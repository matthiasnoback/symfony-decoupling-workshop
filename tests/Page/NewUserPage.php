<?php
declare(strict_types=1);

namespace App\Tests\Page;

use PHPUnit\Framework\Assert;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\DomCrawler\Crawler;

final class NewUserPage
{
    private array $formData = [];

    public function __construct(
        private KernelBrowser $client,
        private Crawler $crawler
    ) {
        Assert::assertStringContainsString('user_new', $crawler->filter('body')->attr('class'));
    }

    public function setName(string $name): self
    {
        $this->formData['user[name]'] = $name;

        return $this;
    }

    public function setEmailAddress(string $emailAddress): self
    {
        $this->formData['user[emailAddress]'] = $emailAddress;

        return $this;
    }

    public function submit(): void
    {
        $this->client->submitForm('Add new user', $this->formData);
    }
}
