<?php

namespace App\Controller;

use App\Entity\Author;
use App\Repository\AuthorRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
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

    #[Route('/api/author', name: 'new_author', methods: ['POST'])]
    public function createSingleAuthor(AuthorRepository $authorRepository, Request $request, SerializerInterface $serializer, EntityManagerInterface $em, UrlGeneratorInterface $generator): JsonResponse
    {
        $serializedBody = $request->getContent();
        $jsonBody = json_decode($serializedBody, true); // => null

        try {
            $isExistingAuthor = $authorRepository->findOneBy(['firstName' => $jsonBody['firstName'], 'lastName' => $jsonBody['lastName']]);
            if ($isExistingAuthor) {
                return new JsonResponse($isExistingAuthor->getFirstName() . " " . $isExistingAuthor->getLastName() . " Already exist !", 200);
            }
        } catch (RouteNotFoundException $e) {
            echo $e;
        }

        $body = $serializer->deserialize($serializedBody, Author::class, "json");
        $em->persist($body);
        $em->flush();
        $jsonAuthor = $serializer->serialize($body, 'json', ['groups' => 'getBooks']);

        $location = $generator->generate("new_author", ['id' => $body->getId()], UrlGeneratorInterface::ABSOLUTE_URL);
        return new JsonResponse($jsonAuthor, 201, ["location" => $location], true);
    }
}
