<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserRequestType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class UserRequestController extends AbstractController
{
    public function __construct(private readonly EntityManagerInterface $em)
    {
    }

    #[Route('/user-request', name: 'user_request')]
    public function index(Request $request): Response
    {
        $user = new User();

        $form = $this->createForm(UserRequestType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($user);
            $this->em->flush();

            // TODO: Send email to admin
            $this->addFlash('success', 'Votre demande a bien été prise en compte. Nous vous contacterons dans les plus brefs délais.');

            return $this->redirectToRoute('user_request');
        }

        return $this->render('user_request/index.html.twig', [
            'form' => $form,
        ]);
    }
}
