<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class UserFormController extends AbstractController
{
    public function __construct(private readonly UserRepository $userRepository)
    {
    }

    #[Route('/form/{token}', name: 'user_form')]
    public function userForm(string $token, Request $request): Response
    {
        $user = $this->userRepository->findOneBy(['token' => $token]);
        if (!$user) {
            throw $this->createNotFoundException('Invalid token');
        }

        dd($user);

        return $this->render('user_form/index.html.twig', [
            'controller_name' => 'UserFormController',
        ]);
    }
}
