<?php

declare(strict_types=1);

namespace App\Message\Handler;

use App\Message\ContentWatchMessage;
use App\Repository\BlogRepository;
use App\Service\ContentWatchApi;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class ContentWatchHandler
{
    /**
     * @param ContentWatchApi $contentWatchApi
     * @param EntityManagerInterface $entityManager
     * @param BlogRepository $blogRepository
     */
    public function __construct(
        private readonly ContentWatchApi $contentWatchApi,
        private readonly EntityManagerInterface $entityManager,
        private readonly BlogRepository $blogRepository,
    )
    {
    }

    /**
     * @param ContentWatchMessage $message
     * @return void
     * @throws \JsonException
     */
    public function __invoke(ContentWatchMessage $message): void
    {
        $blogId = $message->getBlogId();
        $blog = $this->blogRepository->find($blogId);

        if ($blog) {
            $blog->setPercent($this->contentWatchApi->checkText($blog->getText()));
            $this->entityManager->flush();
        }
    }
}
