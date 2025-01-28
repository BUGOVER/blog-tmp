<?php

declare(strict_types=1);

namespace App\Dbal\Event\Blog;

use App\Dbal\EnumTypes\BlogStatus;
use App\Entity\Blog;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Events;

#[AsEntityListener(event: Events::preUpdate, method: Events::preUpdate, entity: Blog::class)]
#[AsEntityListener(event: Events::postFlush, method: Events::postFlush, entity: Blog::class)]
class BlogBlockedAtEvent
{
    public function preUpdate(Blog $blog, PreUpdateEventArgs $eventArgs): void
    {
        if (BlogStatus::blocked->value === $blog->getStatus()->value && null === $blog->getBlockedAt()) {
            $blog->setBlockedAt(new DateTime());
        }

        if (39 >= $blog->getPercent()) {
            $blog->setBlockedAt(new DateTime());
        }
    }
}
