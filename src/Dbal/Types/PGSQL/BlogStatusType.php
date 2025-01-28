<?php

declare(strict_types=1);

namespace App\Dbal\Types\PGSQL;

use App\Dbal\EnumType;
use App\Dbal\EnumTypes\BlogStatus;

class BlogStatusType extends EnumType
{
    /**
     * @return class-string
     */
    protected function getEnum(): string
    {
        return BlogStatus::class;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return 'blog_status';
    }
}
