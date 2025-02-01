<?php

declare(strict_types=1);

namespace App\Controller;

use App\HttpEvent\SampleMiddleware;
use App\Repository\BlogRepository;
use Kafkiansky\SymfonyMiddleware\Attribute\Middleware;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Middleware([SampleMiddleware::class])]
class DefaultController extends AbstractController
{
    #[Route('/', name: 'blog_default')]
    public function __invoke(BlogRepository $blogRepository): Response
    {
        return $this->render(
            view: 'default/index.html.twig',
            parameters: ['blogs' => $blogRepository->getBlogs()],
            response: new Response('Success'),
        );
    }
}
