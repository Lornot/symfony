<?php

namespace IdeasBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerControllerTest extends WebTestCase
{
    public function testShow()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/user/{user_id}');
    }

    public function testList()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/users');
    }

}
