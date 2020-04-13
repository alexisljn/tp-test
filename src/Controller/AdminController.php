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
        $admin = new Admin();
        $loginForm = $this->createForm(LoginType::class, $admin);

        return $this->render('admin/login.html.twig', [
            'controller_name' => 'AdminController',
            'error' => $authenticationUtils->getLastAuthenticationError(),
            'form' => $loginForm->createView()
        ]);
    }
}
