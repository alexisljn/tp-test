<?php

namespace App\Controller;

use App\Entity\Admin;
use App\Form\LoginType;
use App\Form\RegisterUserType;
use App\Manager\AdminManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index()
    {
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }

    /**
     * @Route("/register", name="register")
     */
   /* public function register(Request $request, AdminManager $adminManager)
    {
        $admin = new Admin();
        $form = $this->createForm(RegisterUserType::class, $admin);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $adminManager->addAdmin($admin);
            $this->addFlash('notice', 'You successfully created an account !');
            return $this->redirectToRoute('login');
        }

        return $this->render('admin/register.html.twig', [
            'form' => $form->createView(),
        ]);
    }*/

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

    // A DEGAGER
    public function home()
    {
        return $this->render('admin/home.html.twig');
    }
}
