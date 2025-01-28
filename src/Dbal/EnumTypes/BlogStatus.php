<?php

declare(strict_types=1);

namespace App\Dbal\EnumTypes;

enum BlogStatus: string
{
    case pending = 'pending';

    case active = 'active';

    case blocked = 'blocked';
}
