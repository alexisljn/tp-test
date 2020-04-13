<?php


namespace App\Tests\Controller;


use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AdminControllerTest extends WebTestCase
{

    public function testOKLogin()
    {
        $client = static::createClient();
        $client->followRedirects(true);
        $crawler = $client->request('GET', '/login');

        $this->assertResponseIsSuccessful();

        $client->submitForm('Submit', [
            'login[email]' => 'admin@admin.fr',
            'login[password]' => 'admin'
        ]);

        $this->assertSelectorTextContains('h1', 'Contacts');
    }

    public function testKOLogin()
    {
        $client = static::createClient();
        $client->followRedirects(true);
        $crawler = $client->request('GET', '/login');

        $this->assertResponseIsSuccessful();

        $client->submitForm('Submit', [
            'login[email]' => 'admin@admin.fr',
            'login[password]' => 'adminus'
        ]);

        $this->assertSelectorTextContains('h1', 'Login');
    }

}