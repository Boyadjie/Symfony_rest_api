<?php

namespace App\Tests;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;

class BookTest extends ApiTestCase
{
    private $client;
    protected function setUp(): void
    {
        // "client" that is acting as the browser
        $this->client = static::createClient();
    }

    public function testGetAllBooks(): void
    {
        // Request a specific page
        $crawler = $this->client->request('GET', '/api/books');
        // Validate a successful response
        $this->assertResponseIsSuccessful();
        $this->assertCount(20, $crawler->toArray());
    }

    public function testGetSingleBook(): void
    {
        $response = $this->client->request('GET', '/api/books/2');
        $this->assertResponseIsSuccessful();
    }
}
