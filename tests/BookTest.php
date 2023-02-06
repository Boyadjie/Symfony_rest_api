<?php

namespace App\Tests;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\Author;
use App\Entity\Book;
use Faker;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class BookTest extends ApiTestCase
{
    protected static $client;
    protected static $serializer;

    protected function setUp(): void
    {
        // "client" that is acting as the browser
        self::$client = static::createClient();
        $this->faker = Faker\Factory::create('fr_FR');

        $encoders = [new XmlEncoder(), new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        self::$serializer = new Serializer($normalizers, $encoders);
    }

    public function testGetAllBooks(): void
    {
        // Request a specific page
        $crawler = self::$client->request('GET', '/api/book');
        // Validate a successful response
        $this->assertResponseIsSuccessful();
        $this->assertCount(20, $crawler->toArray());
    }

    public function testGetSingleBook(): void
    {
        $response = self::$client->request('GET', '/api/book/2');
        $this->assertResponseIsSuccessful();
    }

    public function testCreateBook(): void
    {
        $body = '{
            "title": "Super title",
            "coverText": "Nulla numquam dolor numquam quo. Quas nam nobis consequuntur soluta impedit."
        }';
        $response = self::$client->request('POST','/api/book',['body' => $body]);
        $this->assertResponseIsSuccessful();
    }
}
