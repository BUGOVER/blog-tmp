<?php

declare(strict_types=1);

namespace App\Dto;

class SampleFilterDTO
{
    public function __construct(
        public readonly ?string $title,
        public readonly ?string $description,
        public readonly ?string $text,
        public readonly null|array|string $tags,
    )
    {
    }
}
