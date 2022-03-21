<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/user")
 */
final class UserController extends AbstractController
{
    /**
     * @Route("/list", name="user_list")
     */
    public function list(ManagerRegistry $doctrine): Response
    {
        return $this->render('user/list.html.twig', [
            'users' => $doctrine->getManager()->getRepository(User::class)->findAll()
        ]);
    }
}
