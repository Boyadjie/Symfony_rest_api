<?php

namespace App\Controller;

use App\Repository\BookRepository;
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
    public function getSingleBook(BookRepository $bookRepository, $id, SerializerInterface $serializer) {
        $book = $bookRepository->findOneBy(['id'=>$id]);
        $jsonBook = $serializer->serialize($book, 'json', ['groups' => 'getBooks']);

        return new JsonResponse($jsonBook, 200, [], true);
    }
}
