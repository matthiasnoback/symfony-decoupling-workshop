<?php
declare(strict_types=1);

namespace App\Tests;

use App\Tests\Page\NewTaskPage;
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
        $this->client->followRedirects(false);
    }

    public function testNewAndList(): void
    {
        $this->newTask()
            ->setTask('Test')
            ->setDueDate(2022, 3, 14)
            ->submit();

        $this->assertEmailCount(0);

        $this->listTasks()->taskWithName('Test')->assertDueDateIs(2022, 3, 14);
    }

    public function testEdit(): void
    {
        $this->newTask()
            ->setTask('Test')
            ->setDueDate(2022, 3, 14)
            ->submit();

        $this->listTasks()->taskWithName('Test')->goToEditPage()
            ->setTask('Test')
            ->setDueDate(2022, 4, 15)
            ->submit();

        $this->assertEmailCount(1);

        $email = $this->getMailerMessage();
        $this->assertEmailHeaderSame($email, 'To', 'user@example.com');
        $this->assertEmailHeaderSame($email, 'From', 'no-reply@example.com');
        $this->assertEmailTextBodyContains($email, 'The due date of this task has changed');

        $this->listTasks()->taskWithName('Test')->assertDueDateIs(2022, 4, 15);
    }

    private function newTask(): NewTaskPage
    {
        return new NewTaskPage(
            $this->client,
            $this->client->request('GET', '/task/new')
        );
    }

    private function listTasks(): TaskListPage
    {
        return new TaskListPage(
            $this->client,
            $this->client->request('GET', '/')
        );
    }
}
