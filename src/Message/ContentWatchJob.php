<?php

declare(strict_types=1);

namespace App\Message;

class ContentWatchJob
{
    public function __construct(
        private readonly int $blogId,
    )
    {
    }

    public function getBlogId(): int
    {
        return $this->blogId;
    }
}
