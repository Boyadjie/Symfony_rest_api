<?php

namespace App\Tests;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;

class AuthorTest extends ApiTestCase
{
    private $client;
    protected function setUp(): void
    {
        // "client" that is acting as the browser
        $this->client = static::createClient();
    }

    public function testGetAllAuthors(): void
    {
        // Request a specific page
        $crawler = $this->client->request('GET', '/api/author');
        // Validate a successful response
        $this->assertResponseIsSuccessful();
        $this->assertCount(10, $crawler->toArray());
    }

    public function testGetSingleAuthor(): void
    {
        $response = $this->client->request('GET', '/api/author/2');
        $this->assertResponseIsSuccessful();
    }
}
