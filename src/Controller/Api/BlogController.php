<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Dto\SampleFilterDTO;
use App\Entity\Blog;
use App\Filter\BlogFilter;
use App\Form\BlogType;
use App\Repository\BlogRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

final class BlogController extends AbstractController
{
    #[Route(path: '/api/blog', name: 'api_blog', methods: ['GET'])]
    public function view(BlogRepository $blogRepository): JsonResponse
    {
        $blogs = $blogRepository->getBlogs();

        return $this->json(['blogs' => $blogs], Response::HTTP_OK);
    }

    #[Route(path: '/api/blog', name: 'add_blog', methods: ['POST'], format: 'json')]
    public function add(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $blog = new Blog($this->getUser());
        $form = $this->createForm(BlogType::class, $blog);
        $form->submit($request->toArray());

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($blog);
            $entityManager->flush();

            return $this->json($blog);
        }

        return $this->json((string) $form->getErrors(true, false));
    }

    #[Route(path: '/api/blog/{blog}', name: 'edit_blog', methods: ['PUT'])]
    public function edit(Request $request, Blog $blog, EntityManagerInterface $entityManager): JsonResponse
    {
        $form = $this->createForm(BlogType::class, $blog);
        $form->submit($request->toArray());

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($blog);
            $entityManager->flush();

            return $this->json($blog);
        }

        return $this->json((string) $form->getErrors(true, false));
    }

    #[Route(path: '/api/blog/{blog}', name: 'delete_blog', methods: ['DELETE'])]
    public function delete(Blog $blog, EntityManagerInterface $entityManager): JsonResponse
    {
        $entityManager->remove($blog);
        $entityManager->flush();

        return $this->json([]);
    }

    #[Route(path: '/api/blog/filter', name: 'filter_blog', methods: ['GET'])]
    public function filter(#[MapQueryString] BlogFilter $blogFilter, BlogRepository $blogRepository): JsonResponse
    {
        $blogs = $blogRepository->findByBlogFilter($blogFilter);

        return $this->json($blogs->getQuery()->getResult());
    }

    #[Route(path: '/api/blog/filter/dto', name: 'filter_blog_dto', methods: ['POST'], format: 'json')]
    public function filterByDto(#[MapRequestPayload] SampleFilterDTO $filterDTO)
    {
        dd($filterDTO);
    }
}
