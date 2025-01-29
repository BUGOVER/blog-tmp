<?php

declare(strict_types=1);

namespace App\Message;

use App\Message\Handler\ContentWatchHandler;

/**
 * @link ContentWatchHandler
 */
class ContentWatchMessage
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
