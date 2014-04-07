<?php

namespace Login\LoginBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;
use Symfony\Component\HttpFoundation\Session\Session;
use Login\LoginBundle\Modals\Login;

class DefaultControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');

        $session = new Session(new MockArraySessionStorage());

        //Check if the login session is not set and user is at login page
        $this->assertNull($session->get('login'));
        $this->assertTrue($crawler->filter('html:contains("login")')->count() > 0);
    }

    public function testSearchLoggedOut() {
        $client = static::createClient();
        $crawler = $client->request('GET', '/search');

        //User not logged in so sent to login page
        //Use regex to find login
        $this->assertRegExp('/.*(login).*/', $client->getResponse()->getContent());
    }

}
