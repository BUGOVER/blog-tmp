<?php

declare(strict_types=1);

namespace App\Controller\Coment;

use App\Entity\Blog;
use App\Entity\Comment;
use App\Form\CommentType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CreateCommentController extends AbstractController
{
    #[Route(
        path: '/comment/store/{blog}',
        name: 'store_comment',
        requirements: ['blog' => '\d+'],
        methods: ['POST'],
    )]
    public function __invoke(Request $request, EntityManagerInterface $entityManager, Blog $blog): RedirectResponse
    {
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setBlog($blog);
            $comment->setAuthor($this->getUser());
            $entityManager->persist($comment);
            $entityManager->flush();

            return $this->redirectToRoute('app_blog_show', [
                'id' => $blog->getId(),
                Response::HTTP_OK,
            ]);
        }

        return $this->redirectToRoute('app_blog_show', ['id' => $blog->getId(), Response::HTTP_BAD_REQUEST]);
    }
}
