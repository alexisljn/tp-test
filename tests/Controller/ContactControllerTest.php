<?php


namespace App\Tests\Controller;


use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ContactControllerTest extends WebTestCase
{

    public function testShowContacts()
    {
        $client = static::createClient();
        $client->followRedirects(true);
        $crawler = $client->request('GET', '/');

        $client->submitForm('Submit', [
            'login[email]' => 'admin@admin.fr',
            'login[password]' => 'admin'
        ]);

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Contacts');
    }

    public function testOKCreateContact()
    {
        $client = static::createClient();
        $client->followRedirects(true);
        $crawler = $client->request('GET', '/contact/create');

        $client->submitForm('Submit', [
            'login[email]' => 'admin@admin.fr',
            'login[password]' => 'admin'
        ]);

        $this->assertResponseIsSuccessful();

        $client->submitForm('Submit', [
            'manage_contact[firstname]' => 'Paul',
            'manage_contact[lastname]' => 'Durand',
            'manage_contact[email]' => 'paul.durand@gmail.com',
            'manage_contact[phoneNumber]' => '0258129649'
        ]);

        $this->assertSelectorTextContains('h1', 'Update contact');
    }

    public function testKOCreateContact()
    {
        $client = static::createClient();
        $client->followRedirects(true);
        $crawler = $client->request('GET', '/contact/create');

        $client->submitForm('Submit', [
            'login[email]' => 'admin@admin.fr',
            'login[password]' => 'admin'
        ]);

        $this->assertResponseIsSuccessful();

        $client->submitForm('Submit', [
            'manage_contact[firstname]' => 'Paulo',
            'manage_contact[lastname]' => 'Durandi',
            'manage_contact[email]' => 'paulo.durandi@gmail.it',
            'manage_contact[phoneNumber]' => '0258129649000'
        ]);

        $this->assertSelectorTextContains('p', 'Phone number is not correct');
    }

    public function testOKUpdateContact()
    {
        $client = static::createClient();
        $client->followRedirects(true);
        $crawler = $client->request('GET', '/contact/update/paul.durand@gmail.com');

        $client->submitForm('Submit', [
            'login[email]' => 'admin@admin.fr',
            'login[password]' => 'admin'
        ]);

        $this->assertResponseIsSuccessful();

        $client->submitForm('Submit', [
            'manage_contact[firstname]' => 'Paulo',
            'manage_contact[lastname]' => 'Durand',
            'manage_contact[email]' => 'paul.durand@gmail.com',
            'manage_contact[phoneNumber]' => '0258129649'
        ]);

        $this->assertSelectorTextContains('h1', 'Update contact');
    }

    public function testKOUpdateContact()
    {
        $client = static::createClient();
        $client->followRedirects(true);
        $crawler = $client->request('GET', '/contact/update/paul.durand@gmail.com');

        $client->submitForm('Submit', [
            'login[email]' => 'admin@admin.fr',
            'login[password]' => 'admin'
        ]);

        $this->assertResponseIsSuccessful();

        $client->submitForm('Submit', [
            'manage_contact[firstname]' => 'Paul',
            'manage_contact[lastname]' => 'Durand',
            'manage_contact[email]' => 'paul.durand@gmail',
            'manage_contact[phoneNumber]' => '0258129649'
        ]);

        $this->assertSelectorTextContains('ul li', 'This value is not a valid email address.');
    }

    public function testOKDeleteContact()
    {
        $client = static::createClient();
        $client->followRedirects(true);
        $crawler = $client->request('GET', '/contact/delete/paul.durand@gmail.com');

        $client->submitForm('Submit', [
            'login[email]' => 'admin@admin.fr',
            'login[password]' => 'admin'
        ]);

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Contacts');
    }

    public function testKODeleteContact()
    {
        $client = static::createClient();
        $client->followRedirects(true);
        $crawler = $client->request('GET', '/contact/delete/paul.durand@gmail.com');

        $client->submitForm('Submit', [
            'login[email]' => 'admin@admin.fr',
            'login[password]' => 'admin'
        ]);

        $this->assertResponseStatusCodeSame(404);
    }

}