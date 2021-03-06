<?php


namespace App\Manager;


use App\Entity\Contact;
use App\Repository\AdminRepository;
use App\Repository\ContactRepository;
use Doctrine\ORM\EntityManagerInterface;

class ContactManager
{
    private $contactRepository;
    private $entityManager;

    public function __construct(ContactRepository $contactRepository, EntityManagerInterface $entityManager)
    {
        $this->contactRepository = $contactRepository;
        $this->entityManager = $entityManager;
    }

    public function getContacts()
    {
        return $this->contactRepository->findAll();
    }

    public function manageContact(Contact $contact)
    {
        $this->entityManager->persist($contact);
        $this->entityManager->flush();
    }

    public function deleteContact(Contact $contact)
    {
        $this->entityManager->remove($contact);
        $this->entityManager->flush();
    }
}