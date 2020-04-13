<?php

namespace App\Entity;

use App\Exception\InvalidMailException;
use App\Exception\InvalidNameException;
use App\Exception\InvalidPhoneNumberException;
use App\Exception\TooLongNameException;
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
     * @Assert\NotNull()
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", length=70)
     * @Assert\NotBlank()
     * @Assert\NotNull()
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
     * @throws InvalidMailException
     * @throws InvalidNameException
     * @throws InvalidPhoneNumberException
     * @throws TooLongNameException
     */
    public function isContactInputValid()
    {
        $this->isContactNameValid();
        $this->isContactMailValid();

        if (!is_null($this->phoneNumber)) {
            $this->isContactPhoneNumberValid();
        }

    }

    /**
     * Verify if contact's names are valid
     *
     * @throws InvalidNameException
     * @throws TooLongNameException
     */
    public function isContactNameValid()
    {
        if (empty($this->getFirstname()) || empty($this->getLastname())) {
            throw new InvalidNameException('Firstname and/or Lastname is not correct');
        }

        $this->isContactNameTooLong();
    }

    /**
     * Verify if contact's names aren't too long
     *
     * @throws TooLongNameException
     */
    public function isContactNameTooLong()
    {
        if (strlen($this->getFirstname()) > self::MAX_NAME_LENGTH || strlen($this->getLastname()) > self::MAX_NAME_LENGTH) {
            throw new TooLongNameException('Firstname and/or Lastname is too long');
        }
    }

    /**
     * Verify if contact mail is valid
     *
     * @throws InvalidMailException
     */
    public function isContactMailValid()
    {
        if (!filter_var($this->getEmail(), FILTER_VALIDATE_EMAIL)) {
            throw new InvalidMailException('Email is not correct');
        }
    }

    /**
     * Verify if contact's phone number is valid
     *
     * @throws InvalidPhoneNumberException
     */
    public function isContactPhoneNumberValid()
    {
        if (preg_match('/^\d{10}/', $this->getPhoneNumber()) ==! 1
            ||
            strlen($this->getPhoneNumber()) > self::MAX_PHONE_LENGTH) {
           throw new InvalidPhoneNumberException('Phone number is not correct');
        }
    }

}
