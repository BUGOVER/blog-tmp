<?php

declare(strict_types=1);

namespace App\Dbal\Types\PGSQL;

use App\Dbal\EnumType;
use App\Dbal\EnumTypes\BlogStatus;

final class BlogStatusType extends EnumType
{
    private string $typeName = 'blog_status';

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->typeName;
    }

    /**
     * @return class-string
     */
    protected function getEnum(): string
    {
        return BlogStatus::class;
    }
}
