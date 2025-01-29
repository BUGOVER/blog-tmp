<?php

declare(strict_types=1);

namespace App\Dbal\Event;

use App\Entity\Blog;
use App\Message\ContentWatchMessage;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsDoctrineListener;
use Doctrine\ORM\Event\PostFlushEventArgs;
use Doctrine\ORM\Event\PostPersistEventArgs;
use Doctrine\ORM\Events;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsDoctrineListener(event: Events::postPersist, priority: 500, connection: 'default')]
#[AsDoctrineListener(event: Events::postFlush, priority: 500, connection: 'default')]
class EntitiesPostFlush
{
    private array $blogs = [];

    public function __construct(private readonly MessageBusInterface $bus)
    {
    }

    public function postPersist(PostPersistEventArgs $eventArgs): void
    {
        if ($eventArgs->getObject() instanceof Blog) {
            $this->blogs[] = $eventArgs->getObject();
        }
    }

    public function postFlush(PostFlushEventArgs $eventArgs): void
    {
        /* @var Blog $blog */
        foreach ($this->blogs as $blog) {
            $this->bus->dispatch(new ContentWatchMessage($blog->getId()));
        }
    }
}
