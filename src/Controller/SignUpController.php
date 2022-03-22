<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Form\SignUpType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

final class SignUpController extends AbstractController
{
    /**
     * @Route("/sign-up", name="sign_up")
     */
    public function index(Request $request, ManagerRegistry $doctrine, UserPasswordHasherInterface $passwordHasher): Response
    {
        $form = $this->createForm(SignUpType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var User $user */
            $user = $form->getData();
            $user->setPassword($passwordHasher->hashPassword(
                $user,
                $user->getPassword()
            ));
            $doctrine->getManager()->persist($user);
            $doctrine->getManager()->flush();

            $this->addFlash('success', 'Thanks for signing up! You are now ready to login.');

            return $this->redirectToRoute('user_list');
        }

        return $this->renderForm('user/new.html.twig', ['form' => $form]);
    }
}
