<?php
declare(strict_types=1);

namespace App\Tests\Page;

use PHPUnit\Framework\Assert;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\DomCrawler\Crawler;

final class LoginPage
{
    private array $formData = [];

    public function __construct(
        private KernelBrowser $client,
        Crawler $crawler
    ) {
        Assert::assertStringContainsString('login', $crawler->filter('body')->attr('class'));
    }

    public function setEmailAddress(string $emailAddress): self
    {
        $this->formData['_username'] = $emailAddress;

        return $this;
    }

    public function setPassword(string $password): self
    {
        $this->formData['_password'] = $password;

        return $this;
    }

    public function submit(): void
    {
        $this->client->submitForm('Login', $this->formData);
    }
}
