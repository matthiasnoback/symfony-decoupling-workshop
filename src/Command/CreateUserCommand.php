<?php
declare(strict_types=1);

namespace App\Command;

use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class CreateUserCommand extends Command
{
    public function __construct(
        private ManagerRegistry $doctrine,
        private ValidatorInterface $validator,
        private UserPasswordHasherInterface $passwordHasher
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setName('user:create');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $questionHelper = $this->getHelper('question');
        $style = new SymfonyStyle($input, $output);

        $user = new User();

        $name = $questionHelper->ask($input, $output, new Question('Name: '));
        $emailAddress = $questionHelper->ask($input, $output, new Question('Email address: '));
        $password = $questionHelper->ask($input, $output, new Question('Password: '));
        $password = $this->passwordHasher->hashPassword($user, $password);

        $user->setName($name);
        $user->setEmailAddress($emailAddress);
        $user->setPassword($password);

        $violations = $this->validator->validate($user);
        if (count($violations) > 0) {
            foreach ($violations as $violation) {
                echo $violation;
            }
            return 1;
        }

        $this->doctrine->getManager()->persist($user);
        $this->doctrine->getManager()->flush();

        $style->success('Created user: ' . $user);

        return 0;
    }
}
