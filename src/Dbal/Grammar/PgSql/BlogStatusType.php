<?php

declare(strict_types=1);

namespace App\Dbal\Grammar\PgSql;

use App\Dbal\AbstractEnumType;
use App\Dbal\Type\BlogStatus;

final class BlogStatusType extends AbstractEnumType
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
