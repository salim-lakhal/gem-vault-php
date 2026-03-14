<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ApiControllerTest extends WebTestCase
{
    public function testListGemstones(): void
    {
        $client = static::createClient();
        $client->request('GET', '/api/gemstones');

        self::assertSame(200, $client->getResponse()->getStatusCode());
        self::assertJson($client->getResponse()->getContent());

        $data = json_decode($client->getResponse()->getContent(), true);
        self::assertArrayHasKey('data', $data);
    }

    public function testShowGemstoneNotFound(): void
    {
        $client = static::createClient();
        $client->request('GET', '/api/gemstones/99999');

        self::assertSame(404, $client->getResponse()->getStatusCode());
    }

    public function testListGalleries(): void
    {
        $client = static::createClient();
        $client->request('GET', '/api/galleries');

        self::assertSame(200, $client->getResponse()->getStatusCode());
        self::assertJson($client->getResponse()->getContent());
    }

    public function testShowGalleryNotFound(): void
    {
        $client = static::createClient();
        $client->request('GET', '/api/galleries/99999');

        self::assertSame(404, $client->getResponse()->getStatusCode());
    }
}
