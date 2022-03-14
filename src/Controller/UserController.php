<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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

    /**
     * @Route("/new", name="user_new")
     */
    public function new(Request $request, ManagerRegistry $doctrine): Response
    {
        $form = $this->createForm(UserType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
            $doctrine->getManager()->persist($user);
            $doctrine->getManager()->flush();

            return $this->redirectToRoute('user_list');
        }

        return $this->renderForm('user/new.html.twig', ['form' => $form]);
    }
}
