<?php

namespace App\Entity;

use App\Exception\AlreadyRegisteredEmailException;
use App\Exception\InvalidMailException;
use App\Exception\InvalidNameException;
use App\Exception\InvalidPhoneNumberException;
use App\Exception\TooLongNameException;
use App\Manager\ContactManager;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ContactRepository")
 */
class Contact
{
    private const MAX_NAME_LENGTH = 70;
    private const MAX_PHONE_LENGTH = 10;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=70)
     * @Assert\NotBlank()
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", length=70)
     * @Assert\NotBlank()
     */
    private $lastname;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Assert\Email()
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=10, nullable=true, unique=true)
     */
    private $phoneNumber;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(?string $phoneNumber): self
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    /**
     * Verify if contact's inputs are valid
     *
     * @param ContactManager $contactManager
     * @return bool
     * @throws InvalidMailException
     * @throws InvalidNameException
     * @throws InvalidPhoneNumberException
     * @throws TooLongNameException
     * @throws AlreadyRegisteredEmailException
     */
    public function isContactInputValid(ContactManager $contactManager)
    {
        $this->isContactNameValid();
        $this->isContactMailValid($contactManager);
        $this->isContactPhoneNumberValid($contactManager);

        return true;
    }

    /**
     * Verify if contact's names are valid
     *
     * @throws InvalidNameException
     * @throws TooLongNameException
     */
    private function isContactNameValid()
    {
        if ((empty($this->getFirstname()) || is_null($this->getFirstname()))
            ||
            (empty($this->getLastname()) || is_null($this->getLastname()))) {
            throw new InvalidNameException('Firstname and/or Lastname is not correct');
        }

        $this->isContactNameTooLong();
    }

    /**
     * Verify if contact's names aren't too long
     *
     * @throws TooLongNameException
     */
    private function isContactNameTooLong()
    {
        if (strlen($this->getFirstname()) > self::MAX_NAME_LENGTH || strlen($this->getLastname()) > self::MAX_NAME_LENGTH) {
            throw new TooLongNameException('Firstname and/or Lastname is too long');
        }
    }

    /**
     * Verify if contact mail is valid
     *
     * @param ContactManager $contactManager
     * @throws InvalidMailException
     * @throws AlreadyRegisteredEmailException
     */
    private function isContactMailValid(ContactManager $contactManager)
    {
        if (!filter_var($this->getEmail(), FILTER_VALIDATE_EMAIL)) {
            throw new InvalidMailException('Email is not correct');
        }

        $this->isContactMailUnique($contactManager);
    }

    /**
     * Verify is contact's mail is unique
     *
     * @param ContactManager $contactManager
     * @throws AlreadyRegisteredEmailException
     */
    private function isContactMailUnique(ContactManager $contactManager)
    {
        $contacts = $contactManager->getContacts();

        foreach ($contacts as $contact)
        {
            if ($this->getEmail() === $contact->getEmail()) {
                throw new AlreadyRegisteredEmailException('This email is already registered');
            }
        }
    }

    /**
     * Verify if contact's phone number is valid
     *
     * @param ContactManager $contactManager
     * @throws AlreadyRegisteredEmailException
     * @throws InvalidPhoneNumberException
     */
    private function isContactPhoneNumberValid(ContactManager $contactManager)
    {
        if (preg_match('/^\d{10}/', $this->getPhoneNumber()) ==! 1
            ||
            strlen($this->getPhoneNumber()) > self::MAX_PHONE_LENGTH) {
           throw new InvalidPhoneNumberException('Phone number is not correct');
        }

        $this->isContactPhoneNumberUnique($contactManager);
    }

    /**
     * @param ContactManager $contactManager
     * @throws AlreadyRegisteredEmailException
     */
    private function isContactPhoneNumberUnique(ContactManager $contactManager)
    {
        $contacts = $contactManager->getContacts();

        foreach ($contacts as $contact)
        {
            if ($this->getPhoneNumber() === $contact->getPhoneNumber()) {
                throw new AlreadyRegisteredEmailException('This email is already registered');
            }
        }
    }
}
