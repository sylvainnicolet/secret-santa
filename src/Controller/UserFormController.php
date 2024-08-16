<?php

namespace App\Controller;

use App\Form\UserFormType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class UserFormController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly UserRepository $userRepository
    ) {
    }

    #[Route('/form/{token}', name: 'user_form')]
    public function userForm(string $token, Request $request): Response
    {
        $user = $this->userRepository->findOneBy(['token' => $token]);
        if (!$user) {
            throw $this->createNotFoundException('Invalid token');
        }

        $form = $this->createForm(UserFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->flush();

            // TODO: Redirect to a "thank you" page
            return $this->redirectToRoute('user_form');
        }

        return $this->render('user_form/index.html.twig', [
            'form' => $form,
            'user' => $user,
        ]);
    }
}
