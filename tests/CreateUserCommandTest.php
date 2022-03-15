<?php
declare(strict_types=1);

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\ApplicationTester;

final class CreateUserCommandTest extends KernelTestCase
{
    public function testCreateUser(): void
    {
        $kernel = self::createKernel();
        $application = new Application($kernel);

        $application->setCatchExceptions(false);
        $application->setAutoExit(false);

        $tester = new ApplicationTester($application);
        $tester->setInputs(['Matthias', 'matthiasnoback@gmail.com']);

        $tester->run([
            'command'=> 'user:create',
        ], ['interactive' => true]);

        self::assertStringContainsString('Created user: Matthias', $tester->getDisplay());
    }
}
