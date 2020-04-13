<?php

namespace App\Controller;

use App\Entity\Admin;
use App\Form\LoginType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AdminController extends AbstractController
{
    /**
     * @Route("/login", name="login")
     */
    public function login(AuthenticationUtils $authenticationUtils)
    {
        // Penser dans la navbar à faire un switch entre le bouton logout et le bouton login
        // Afficher le form
        // Si le form est envoyé, le traiter
        // Rediriger vers la page d'accueil

        $admin = new Admin();
        $loginForm = $this->createForm(LoginType::class, $admin);

        return $this->render('admin/login.html.twig', [
            'controller_name' => 'AdminController',
            'error' => $authenticationUtils->getLastAuthenticationError(),
            'form' => $loginForm->createView()
        ]);
    }
}
