<?php
declare(strict_types=1);

namespace App\Controller;

use App\Form\LoginType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

final class LoginController extends AbstractController
{
    public function __construct(
        private AuthenticationUtils $authenticationUtils,
        private FormFactoryInterface $formFactory,
    ) {
    }

    /**
     * @Route("/login", name="login")
     */
    public function index(): Response
    {
        // get the login error if there is one
        $form = $this->formFactory->createNamed('', LoginType::class, [
            '_username' => $this->authenticationUtils->getLastUsername() // last username entered by the user
        ]);
        $error = $this->authenticationUtils->getLastAuthenticationError();
        if ($error instanceof AuthenticationException) {
            $form->addError(new FormError($error->getMessageKey()));
        }

        return $this->render('login/index.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
