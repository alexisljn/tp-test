<?php


namespace App\Tests\Entity;


use App\Entity\Contact;
use App\Exception\InvalidMailException;
use App\Exception\InvalidNameException;
use App\Exception\InvalidPhoneNumberException;
use App\Exception\TooLongNameException;
use PHPUnit\Framework\TestCase;

class ContactTest extends TestCase
{
    private $contact;

    public function setUp(): void
    {
        $this->contact = new Contact();
        $this->contact->setFirstname('Paul');
        $this->contact->setLastname('Durand');
        $this->contact->setEmail('paul.durand@gmail.com');
        $this->contact->setPhoneNumber('0123252421');
    }

    public function testOKIsContactInputValid()
    {
        $this->contact->isContactInputValid();
        $this->assertTrue(true);
    }

    public function testKOIsContactInputValid()
    {
        $this->contact->setEmail('pas un email ce truc');
        $this->expectException(\Exception::class);
        $this->contact->isContactInputValid();
    }

    public function testOKIsContactNameValid()
    {
        $this->contact->isContactNameValid();
        $this->assertTrue(true);
    }

    public function testEmptyIsContactNameValid()
    {
        $this->contact->setFirstname('');;
        $this->expectException(InvalidNameException::class);
        $this->contact->isContactNameValid();
    }

    public function testOKIsContactNameTooLong()
    {
        $this->contact->isContactNameTooLong();
        $this->assertTrue(true);
    }

    public function testKOIsContactNameTooLong()
    {
        $this->contact->setLastname('Lsdqdqsdfgfsqdfdsfsdfgfdgfgjhhgkjhulouioohefdsfhghgfhfgfdgdfgsdfdqsfqzsfddsfsdf');
        $this->expectException(TooLongNameException::class);
        $this->contact->isContactNameTooLong();
    }

    public function testOKIsContactMailValid()
    {
        $this->contact->isContactMailValid();
        $this->assertTrue(true);
    }

    public function testKOEmptyIsContactMailValid()
    {
        $this->contact->setEmail('');
        $this->expectException(InvalidMailException::class);
        $this->contact->isContactMailValid();
    }

    public function testKOInvalidIsContactMailValid()
    {
        $this->contact->setEmail('toto#toto.fr');
        $this->expectException(InvalidMailException::class);
        $this->contact->isContactMailValid();
    }

    public function testOKIsContactPhoneNumberValid()
    {
        $this->contact->isContactPhoneNumberValid();
        $this->assertTrue(true);
    }

    public function testKOEmptyIsContactPhoneNumberValid()
    {
        $this->contact->setPhoneNumber('');
        $this->expectException(InvalidPhoneNumberException::class);
        $this->contact->isContactPhoneNumberValid();
    }

    public function testKOInvalidIsContactPhoneNumberValid()
    {
        $this->contact->setPhoneNumber('commentallezvous');
        $this->expectException(InvalidPhoneNumberException::class);
        $this->contact->isContactPhoneNumberValid();
    }

    public function testKOTooLongPhoneNumber()
    {
        $this->contact->setPhoneNumber('01202020202020');
        $this->expectException(InvalidPhoneNumberException::class);
        $this->contact->isContactPhoneNumberValid();
    }

    public function tearDown()
    {
        unset($this->contact);
    }

}