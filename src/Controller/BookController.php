<?php

namespace App\Controller;

use App\Repository\BookRepository;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class BookController extends AbstractController
{
    #[Route('/api/books', name: 'app_books', methods: ['GET'])]
    public function getAllBooks(BookRepository $bookRepository, SerializerInterface $serializer): JsonResponse
    {
        $bookList = $bookRepository->findAll();
        $jsonBookList = $serializer->serialize($bookList, 'json');

        return new JsonResponse($jsonBookList, 200, [], true);
    }

    #[Route('/api/books/{id}', name: 'app_one_book', methods: ['GET'])]
    public function getSingleBook(BookRepository $bookRepository, $id, SerializerInterface $serializer) {
        $book = $bookRepository->findOneBy(['id'=>$id]);
        $jsonBook = $serializer->serialize($book, 'json');

        return new JsonResponse($jsonBook, 200, [], true);
    }
}
