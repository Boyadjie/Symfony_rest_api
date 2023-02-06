<?php

namespace App\Controller;

use App\Entity\Book;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class BookController extends AbstractController
{
    #[Route('/api/book', name: 'app_book', methods: ['GET'])]
    public function getAllBooks(BookRepository $bookRepository, SerializerInterface $serializer): JsonResponse
    {
        $bookList = $bookRepository->findAll();
        $jsonBookList = $serializer->serialize($bookList, 'json', ['groups' => 'getBooks']);

        return new JsonResponse($jsonBookList, 200, [], true);
    }

    #[Route('/api/book/{id}', name: 'app_single_book', methods: ['GET'])]
    public function getSingleBook(BookRepository $bookRepository, $id, SerializerInterface $serializer): JsonResponse
    {
        $book = $bookRepository->findOneBy(['id'=>$id]);
        $jsonBook = $serializer->serialize($book, 'json', ['groups' => 'getBooks']);

        return new JsonResponse($jsonBook, 200, [], true);
    }

    #[Route('/api/book', name: 'new_book', methods: ['POST'])]
    public function createSingleBook(BookRepository $bookRepository, Request $request, SerializerInterface $serializer, EntityManagerInterface $em, UrlGeneratorInterface $generator): JsonResponse
    {
        $serializedBody = $request->getContent();
        $jsonBody = json_decode($serializedBody, true); // => null

        try {
            $isExistingBook = $bookRepository->findOneBy(['title' => $jsonBody['title']]);
            if ($isExistingBook) {
                return new JsonResponse($isExistingBook->getTitle() . " Already exist !", 200);
            }
        } catch (RouteNotFoundException $e) {
            echo $e;
        }

        $body = $serializer->deserialize($serializedBody, Book::class, "json");
        $em->persist($body);
        $em->flush();
        $jsonBook = $serializer->serialize($body, 'json', ['groups' => 'getBooks']);

        $location = $generator->generate("new_book", ['id' => $body->getId()], UrlGeneratorInterface::ABSOLUTE_URL);
        return new JsonResponse($jsonBook, 201, ["location" => $location], true);
    }
}
