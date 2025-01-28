<?php

declare(strict_types=1);

namespace App\Dbal\Event\Blog;

use App\Dbal\EnumTypes\BlogStatus;
use App\Entity\Blog;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Event\PostUpdateEventArgs;
use Doctrine\ORM\Events;

#[AsEntityListener(event: Events::postUpdate, method: Events::postUpdate, entity: Blog::class)]
class BlogBlockedAtEvent
{
    public function postUpdate(Blog $blog, PostUpdateEventArgs $eventArgs): void
    {
        if (BlogStatus::blocked->value === $blog->getStatus()->value && null === $blog->getBlockedAt()) {
            $blog->setBlockedAt(new DateTime());
        }

        if (39 >= $blog->getPercent()) {
            $blog->setBlockedAt(new DateTime());
        }

        if (40 <= $blog->getPercent()) {
            $blog->setBlockedAt(null);
        }
    }
}
