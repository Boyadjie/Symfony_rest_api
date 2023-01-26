<?php

namespace App\Controller;

use App\Repository\AuthorRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class AuthorController extends AbstractController
{
    #[Route('/api/author', name: 'app_author', methods: ['GET'])]
    public function getAllAuthors(AuthorRepository $authorRepository, SerializerInterface $serializer): JsonResponse
    {
        $authorList = $authorRepository->findAll();
        $jsonAuthorList = $serializer->serialize($authorList, 'json', ['groups' => 'getBooks']);

        return new JsonResponse($jsonAuthorList, 200, [], true);
    }

    #[Route('/api/author/{id}', name: 'app_single_author', methods: ['GET'])]
    public function getSingleAuthor(AuthorRepository $authorRepository, $id, SerializerInterface $serializer) {
        $author = $authorRepository->findOneBy(['id'=>$id]);
        $jsonAuthor = $serializer->serialize($author, 'json', ['groups' => 'getBooks']);

        return new JsonResponse($jsonAuthor, 200, [], true);
    }
}
