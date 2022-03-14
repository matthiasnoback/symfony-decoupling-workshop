<?php
declare(strict_types=1);

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class TaskApiTest extends WebTestCase
{
    private KernelBrowser $client;

    protected function setUp(): void
    {
        $this->client = self::createClient();
        $this->client->followRedirects();
    }

    public function testNewAndList(): void
    {
        $this->sendJsonPostRequest(
            '/api/task/new',
            <<<'JSON'
            {
                "task": "Test",
                "dueDate": "2022-03-14"
            }
            JSON
        );

        self::assertEquals(
            [
                [
                    'id' => 1,
                    'task' => 'Test',
                    'dueDate' => '2022-03-14',
                    'notes' => [],
                    'assignedTo' => null
                ]
            ],
            $this->sendJsonGetRequest('/api/task/list')
        );
    }

    public function testEdit(): void
    {
        $this->sendJsonPostRequest(
            '/api/task/new',
            <<<'JSON'
            {
                "task": "Test",
                "dueDate": "2022-03-14"
            }
            JSON
        );

        $this->sendJsonPostRequest(
            '/api/task/edit/1',
            <<<'JSON'
            {
                "task": "Modified",
                "dueDate": "2022-04-15"
            }
            JSON
        );

        self::assertEquals(
            [
                [
                    'id' => 1,
                    'task' => 'Modified',
                    'dueDate' => '2022-04-15',
                    'notes' => [],
                    'assignedTo' => null
                ]
            ],
            $this->sendJsonGetRequest('/api/task/list')
        );
    }

    private function sendJsonPostRequest(string $url, string $data): void
    {
        $this->client->request('POST', $url, [], [], [], $data);
    }

    private function sendJsonGetRequest(string $url): array
    {
        $this->client->request('GET', $url);

        $responseContent = $this->client->getResponse()->getContent();
        self::assertJson($responseContent);

        $decodedContent = json_decode($this->client->getResponse()->getContent(), true);

        self::assertIsArray($decodedContent);

        return $decodedContent;
    }
}
