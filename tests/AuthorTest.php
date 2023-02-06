<?php

namespace App\Tests;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;

class AuthorTest extends ApiTestCase
{
    protected static $client;
    protected function setUp(): void
    {
        // "client" that is acting as the browser
        self::$client = static::createClient();

    }

    public function testGetAllAuthors(): void
    {
        // Request a specific page
        $crawler = self::$client->request('GET', '/api/author');
        // Validate a successful response
        $this->assertResponseIsSuccessful();
        $this->assertCount(10, $crawler->toArray());
    }

    public function testGetSingleAuthor(): void
    {
        $response = self::$client->request('GET', '/api/author/2');
        $this->assertResponseIsSuccessful();
    }

    public function testCreateBook(): void
    {
        $body = '{
            "firstName": "Pedro",
            "lastName": "Dupont"
        }';
        $response = self::$client->request('POST','/api/author',['body' => $body]);
        $this->assertResponseIsSuccessful();
    }
}
