<?php

namespace App\Tests;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use Faker;

class BookTest extends ApiTestCase
{
    protected static $client;
    protected function setUp(): void
    {
        // "client" that is acting as the browser
        self::$client = static::createClient();
        $this->faker = Faker\Factory::create('fr_FR');
    }

    public function testGetAllBooks(): void
    {
        // Request a specific page
        $crawler = self::$client->request('GET', '/api/book');
        // Validate a successful response
        $this->assertResponseIsSuccessful();
        $this->assertCount(21, $crawler->toArray());
    }

    public function testGetSingleBook(): void
    {
        $response = self::$client->request('GET', '/api/book/2');
        $this->assertResponseIsSuccessful();
    }

    public function testCreateBook(): void
    {
        $body = '{
            "title": "Super title 2",
            "coverText": "Nulla numquam dolor numquam quo. Quas nam nobis consequuntur soluta impedit.",
            "author": {
                "firstName": "Pedro",
                "lastName": "Dupont"
            }
        }';
        $response = self::$client->request('POST','/api/book',['body' => $body]);
        $this->assertResponseIsSuccessful();
    }
}
