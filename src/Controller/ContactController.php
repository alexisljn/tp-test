<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ManageContactType;
use App\Manager\ContactManager;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    /**
     * @Route("/contact/create", name="create-contact")
     * @param Request $request
     * @param ContactManager $contactManager
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
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
                return $this->redirectToRoute('update_contact', ['email' => $contact->getEmail()]);
            } catch (\Exception $e) {
                $error = $e->getMessage();

                if ($e instanceof UniqueConstraintViolationException)
                {
                    $param = (explode(' ', explode('entry ', $e->getMessage())[1])[0]);
                    $error = $param . ' is already registered by a user';
                }
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
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showContacts(ContactManager $contactManager)
    {
        $contacts = $contactManager->getContacts();

        return $this->render('contact/show-contacts.html.twig', [
            'contacts' => $contacts
        ]);
    }

    /**
     * @Route("/contact/update/{email}", name="update_contact")
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
                return $this->redirectToRoute('update_contact', ['email' => $contact->getEmail()]);
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
     * @Route("/contact/delete/{email}", name="delete_contact")
     * @param Contact $contact
     * @param ContactManager $contactManager
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteContact(Contact $contact, ContactManager $contactManager)
    {
        $contactManager->deleteContact($contact);

        return $this->redirectToRoute('show-contacts');
    }
}
