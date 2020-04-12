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
    public function createContact(Request $request, ContactManager $contactManager)
    {
        $contact = new Contact();
        $createForm = $this->createForm(ManageContactType::class, $contact);
        $createForm->handleRequest($request);
        $error = null;

        if ($createForm->isSubmitted() && $createForm->isValid()) {
            try {
                $contact->isContactInputValid($contactManager);
                $contactManager->manageContact($contact);
                return $this->redirectToRoute('update_contact', ['id' => $contact->getId()]);
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

    /**
     * @Route("/contact/update/{id}", name="update_contact")
     */
    public function updateContact(Request $request ,Contact $contact, ContactManager $contactManager)
    {
        $updateForm = $this->createForm(ManageContactType::class, $contact);
        $updateForm->handleRequest($request);
        $error = null;

        if ($updateForm->isSubmitted() && $updateForm->isValid()) {
            try {
                $contact->isContactInputValid($contactManager);
                $contactManager->manageContact($contact);
                return $this->redirectToRoute('update_contact', ['id' => $contact->getId()]);
            } catch (\Exception $e) {
                $error = $e->getMessage();
            }
        }

        return $this->render('contact/update-contact.html.twig', [
            'form' => $updateForm->createView(),
            'error' => $error
        ]);
    }

    /**
     * @Route("/contact/delete/{id}", name="delete_contact")
     * @param Contact $contact
     * @param ContactManager $contactManager
     */
    public function deleteContact(Contact $contact, ContactManager $contactManager)
    {
        $contactManager->deleteContact($contact);

        return $this->redirectToRoute('show-contacts');
    }
}
