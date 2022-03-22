<?php
declare(strict_types=1);

namespace App\Tests\Page;

use DOMAttr;
use PHPUnit\Framework\Assert;
use RuntimeException;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\DomCrawler\Crawler;

final class NewTaskPage
{
    private array $formData = [];

    public function __construct(
        private KernelBrowser $client,
        private Crawler $crawler
    ) {
        Assert::assertStringContainsString('task_new', $crawler->filter('body')->attr('class'));
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

    public function submit(): void
    {
        $this->client->submitForm('Save', $this->formData);
    }

    public function submitInvalidData(): self
    {
        return new self($this->client, $this->client->submitForm('Save', $this->formData));
    }

    public function setAssignedTo(string $name): self
    {
        $this->formData['task[assignedTo]'] = $this->userIdForName($name);

        return $this;
    }

    private function userIdForName($name): string
    {
        foreach ($this->crawler->filter('select[name="task[assignedTo]"] option') as $optionNode) {
            if (str_contains($optionNode->textContent, $name)) {
                /** @var DOMAttr $valueAttribute */
                $valueAttribute = $optionNode->attributes['value'];
                return $valueAttribute->value;
            }
        }

        throw new RuntimeException('Could not find user');
    }

    public function assertFormHasError(string $expectedError): void
    {
        Assert::assertStringContainsString(
            $expectedError,
            $this->crawler->filter('.invalid-feedback')->text()
        );
    }
}
