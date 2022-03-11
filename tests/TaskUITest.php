<?php
declare(strict_types=1);

namespace App\Tests;

use App\Tests\Page\TaskListPage;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class TaskUITest extends WebTestCase
{
    private KernelBrowser $client;

    protected function setUp(): void
    {
        parent::setUp();

        $this->client = self::createClient();
        $this->client->followRedirects();
    }

    public function testNewAndList(): void
    {
        $this->client->request('GET', '/task/new');
        $this->client->submitForm('Save', [
            'task[task]' => 'Test',
            'task[dueDate][day]' => '14',
            'task[dueDate][month]' => '3',
            'task[dueDate][year]' => '2022',
        ]);

        $this->listTasks()->taskWithName('Test')->assertDueDateIs(2022, 3, 14);
    }

    public function testEdit(): void
    {
        $this->client->request('GET', '/task/new');
        $this->client->submitForm('Save', [
            'task[task]' => 'Test',
            'task[dueDate][day]' => '14',
            'task[dueDate][month]' => '3',
            'task[dueDate][year]' => '2022',
        ]);

        $this->listTasks()->taskWithName('Test')->goToEditPage()
            ->setTask('Test')
            ->setDueDate(2022, 4, 15)
            ->submit();

        $this->listTasks()->taskWithName('Test')->assertDueDateIs(2022, 4, 15);
    }

    private function listTasks(): TaskListPage
    {
        return new TaskListPage(
            $this->client,
            $this->client->request('GET', '/')
        );
    }
}
