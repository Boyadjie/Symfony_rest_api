<?php

namespace App\DataFixtures;

use App\Entity\Author;
use App\Entity\Book;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create('fr_FR');

        $authorsArray = [];
        for ($j = 0; $j < 10; $j++) {
            $author = new Author;
            $author->setFirstName($faker->firstName());
            $author->setLastName($faker->lastName());
            array_push($authorsArray, $author);
            $manager->persist($author);
        }

        for ($i = 0; $i < 20; $i++) {
            $book = new Book;
            $book->setTitle($faker->word());
            $book->setCoverText($faker->text());

            $randKey = array_rand($authorsArray);
            $book->setAuthor($authorsArray[$randKey]);
            $manager->persist($book);
        }

        $manager->flush();
    }
}
