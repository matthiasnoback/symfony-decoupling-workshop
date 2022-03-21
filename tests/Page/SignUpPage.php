<?php
declare(strict_types=1);

namespace App\Tests\Page;

use PHPUnit\Framework\Assert;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\DomCrawler\Crawler;

final class SignUpPage
{
    private array $formData = [];

    public function __construct(
        private KernelBrowser $client,
        Crawler $crawler
    ) {
        Assert::assertStringContainsString('sign_up', $crawler->filter('body')->attr('class'));
    }

    public function setName(string $name): self
    {
        $this->formData['sign_up[name]'] = $name;

        return $this;
    }

    public function setEmailAddress(string $emailAddress): self
    {
        $this->formData['sign_up[emailAddress]'] = $emailAddress;

        return $this;
    }

    public function submit(): void
    {
        $this->client->submitForm('Sign up', $this->formData);
    }
}
