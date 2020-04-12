<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ManageContactType;
use App\Manager\ContactManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    /**
     * @Route("/contact", name="contact")
     */
    public function index()
    {
        return $this->render('contact/index.html.twig', [
            'controller_name' => 'ContactController',
        ]);
    }

    /**
     * @Route("/contact/create", name="create-contact")
     */
    public function createContact(Request $request, string $error = null, ContactManager $contactManager)
    {
        $contact = new Contact();
        $createForm = $this->createForm(ManageContactType::class, $contact);
        $createForm->handleRequest($request);

        if ($createForm->isSubmitted() && $createForm->isValid()) {
            try {
                $contact->isContactInputValid($contactManager);
                $contactManager->createContact($contact);
                return $this->redirectToRoute('show-contacts'); // Redir à changer quand show contact
            } catch (\Exception $e) {
                $error = $e->getMessage();
            }
        }

        return $this->render('contact/create-contact.html.twig', [
            'form' => $createForm->createView(),
            'error' => $error
        ]);
    }

    /**
     * @Route("/", name="show-contacts")
     * @param ContactManager $contactManager
     */
    public function showContacts(ContactManager $contactManager)
    {
        $contacts = $contactManager->getContacts();

        return $this->render('contact/show-contacts.html.twig', [
            'contacts' => $contacts
        ]);
    }
}
