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

        for ($i = 0; $i < 20; $i++) {
            $book = new Book;
            $book->setTitle($faker->word());
            $book->setCoverText($faker->text());
            $manager->persist($book);
        }

        for ($j = 0; $j < 10; $j++) {
            $author = new Author;
            $author->setFirstName($faker->firstName());
            $author->setLastName($faker->lastName());
        }
        $manager->flush();
    }
}
