<?php
declare(strict_types=1);

namespace App\Controller;

use LogicException;
use Symfony\Component\Routing\Annotation\Route;

final class LogoutController
{
    /**
     * @Route("/logout", name="logout")
     */
    public function index(): void
    {
        throw new LogicException('Not supposed to be executed');
    }
}
