<?php

namespace IdeasApiBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class IdeaControllerTest extends WebTestCase
{
    public function testGetideas()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/api/getIdeas');
    }

    public function testGetidea()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/api/get_idea/{idea_id}');
    }

    public function testAddidea()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/api/add_idea');
    }

    public function testUpdate()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/api/ideas/update/{idea_id}');
    }

}
